# **Methods**

## Differential Expression Workflow

### 1. Filter Data

```python
valid = adata.obs[cluster_key].value_counts()
valid = valid[valid > 3].index          # Keep only types with ≥4 cells
adata   = adata[adata.obs[cluster_key].isin(valid)]
```

> Purpose: Remove clusters with too few cells to avoid distortion in the Wilcoxon test.

### 2. Differential Analysis

```python
sc.tl.rank_genes_groups(
    adata,
    groupby = cluster_key,
    method  = 'wilcoxon',   # Non-parametric rank-sum test
    key_added = 'rank_genes_groups'
)
```

Scanpy computes for each cluster:

* log2FC (current cluster vs. all other cells)
* p-value → BH correction → adjusted p-value

### 3. pct.1 / pct.2 Calculation

```python
X = adata.raw.X                      # Sparse matrix
for g in groups:
    grp_idx = np.where(obs == g)[0]
    oth_idx = np.setdiff1d(all_idx, grp_idx)
    pct1[g] = np.asarray(X[grp_idx ].astype(bool).mean(0)).ravel()
    pct2[g] = np.asarray(X[oth_idx].astype(bool).mean(0)).ravel()
```

* **pct.1** = detection rate of the gene in the current cluster
* **pct.2** = detection rate of the gene in all other clusters

---

## Offline Enrichment Analysis Workflow

### 1. Input

```json
{
  "significant_genes": [...],   // List of DEGs
  "background_genes": [...],    // Background gene list
  "gene_sets": "GO_bp.gmt"      // GMT file name
}
```

> Supports JSON string or Python list.

### 2. GMT Files

```bash
wget -O KEGG_2019_Human.gmt "http://maayanlab.cloud/Enrichr/geneSetLibrary?mode=text&libraryName=KEGG_2019_Human"
wget -O go_bp_2018.gmt "http://maayanlab.cloud/Enrichr/geneSetLibrary?mode=text&libraryName=GO_Biological_Process_2018"
wget -O go_mf_2018.gmt "http://maayanlab.cloud/Enrichr/geneSetLibrary?mode=text&libraryName=GO_Molecular_Function_2018"
wget -O go_cc_2018.gmt "http://maayanlab.cloud/Enrichr/geneSetLibrary?mode=text&libraryName=GO_Cellular_Component_2018"
```

### 3. Enrichment Analysis

```python
enrich_results = gp.enrich(
    gene_list   = significant_genes,
    gene_sets   = gene_sets_list,
    background  = background_genes,
    verbose     = False
)
```

* Method: `enrich` in **gseapy 0.10+** (hypergeometric test).
* The background list is used for computing odds ratio and p-value.

### 4. Filtering & Output

```python
df = enrich_results.results
df = df[df['Adjusted P-value'] <= 0.05]   # Keep significant terms
df = df.rename(columns={
    'Term':'t',
    'Adjusted P-value':'p',
    'Odds Ratio':'o',
    'Combined Score':'c',
    'Genes':'g'
})
print(df.to_json(orient='records'))
```

Returned fields:

* `t` = pathway name
* `p` = adjusted p-value
* `o` = odds ratio
* `c` = combined score
* `g` = matched genes


