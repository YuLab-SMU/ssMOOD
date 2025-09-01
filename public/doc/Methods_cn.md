## 差异表达流程

### 1. 过滤数据
```python
valid = adata.obs[cluster_key].value_counts()
valid = valid[valid > 3].index          # 仅保留 ≥4 个细胞的类型
adata   = adata[adata.obs[cluster_key].isin(valid)]
```

> 目的：去掉细胞数过少的类，避免 wilcoxon 检验失真。

### 2. 差异分析
```python
sc.tl.rank_genes_groups(
    adata,
    groupby = cluster_key,
    method  = 'wilcoxon',   # 非参秩和检验
    key_added = 'rank_genes_groups'
)
```
Scanpy 对每个类型分别计算：  
- log2FC（当前类 vs 其余全部细胞）  
- p 值 → BH 校正得 p-adj

### 3. pct.1 / pct.2 计算
```python
X = adata.raw.X                      # 稀疏矩阵
for g in groups:
    grp_idx = np.where(obs == g)[0]
    oth_idx = np.setdiff1d(all_idx, grp_idx)
    pct1[g] = np.asarray(X[grp_idx ].astype(bool).mean(0)).ravel()
    pct2[g] = np.asarray(X[oth_idx].astype(bool).mean(0)).ravel()
```
pct.1 = 当前类中该基因检出比例  
pct.2 = 其余类中该基因检出比例


## 离线富集分析流程

### 1. 输入
```json
{
  "significant_genes": [...],   // 差异基因列表
  "background_genes": [...],    // 背景基因列表
  "gene_sets": "GO_bp.gmt"      // GMT 文件名
}
```
> 支持 JSON 字符串或列表。

### 2. GMT文件
```bash
wget -O KEGG_2019_Human.gmt "http://maayanlab.cloud/Enrichr/geneSetLibrary?mode=text&libraryName=KEGG_2019_Human"
wget -O go_bp_2018.gmt "http://maayanlab.cloud/Enrichr/geneSetLibrary?mode=text&libraryName=GO_Biological_Process_2018"
wget -O go_mf_2018.gmt "http://maayanlab.cloud/Enrichr/geneSetLibrary?mode=text&libraryName=GO_Molecular_Function_2018"
wget -O go_cc_2018.gmt "http://maayanlab.cloud/Enrichr/geneSetLibrary?mode=text&libraryName=GO_Cellular_Component_2018"

```


### 3. 富集分析
```python
enrich_results = gp.enrich(
    gene_list   = significant_genes,
    gene_sets   = gene_sets_list,
    background  = background_genes,
    verbose     = False
)
```
- 方法：`gseapy 0.10+` 的 `enrich`（超几何检验）。  
- 背景用于计算 odds ratio 与 p 值。

### 4. 过滤 & 输出
```python
df = enrich_results.results
df = df[df['Adjusted P-value'] <= 0.05]   # 保留显著
df = df.rename(columns={
    'Term':'t',
    'Adjusted P-value':'p',
    'Odds Ratio':'o',
    'Combined Score':'c',
    'Genes':'g'
})
print(df.to_json(orient='records'))
```
返回字段：`t` 通路名称，`p` 校正后 p 值，`o` 比值比，`c` 综合评分，`g` 命中基因。翻译为英文