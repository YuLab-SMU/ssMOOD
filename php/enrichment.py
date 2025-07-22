############################
#功能：基因富集分析
#作者：李金晨
#时间：20250515
############################

#./offline_enrich_db/目录下为离线数据库

#配置缓存目录为当前目录
#请给予运行目录777权限
import os
# ⚠️设置 matplotlib 缓存目录，避免无权限报错，必须在引入其他库之前设置！！！
script_dir = os.path.dirname(os.path.realpath(__file__))
cache_dir = os.path.join(script_dir, '.cache')
os.environ['MPLCONFIGDIR'] = cache_dir
import sys
import json
import pandas as pd
import gseapy as gp



def read_gmt(gmt_path):
    """读取本地 GMT 文件为字典格式"""
    gene_sets = {}
    with open(gmt_path) as f:
        for line in f:
            parts = line.strip().split('\t')
            term = parts[0]
            genes = parts[2:]
            gene_sets[term] = genes
    return gene_sets

def main():
    input_data = sys.stdin.read()
    data = json.loads(input_data)

    significant_genes = data['significant_genes']
    background_genes = data['background_genes']
    gene_sets_name = data['gene_sets']

    # 如果是字符串（从 PHP 传来时可能是 JSON string），转换为列表
    if isinstance(significant_genes, str):
        significant_genes = significant_genes.strip("[]").replace('"', '').split(',')
    if isinstance(background_genes, str):
        background_genes = background_genes.strip("[]").replace('"', '').split(',')

    gene_sets_list = read_gmt(os.path.join(script_dir, "offline_enrich_db", gene_sets_name))

    enrich_results = gp.enrich(
        gene_list=significant_genes,
        gene_sets=gene_sets_list,
        background=background_genes,
        verbose=False,
        outdir=None
    )

    results = enrich_results.results
    if results.empty:
        print("[]")
        return

    # 清洗、筛选和重命名结果
    results = results.drop(columns=['Gene_set', 'Overlap', 'P-value'], errors='ignore')
    results = results[results['Adjusted P-value'] <= 0.05]
    results = results.reset_index(drop=True)

    results = results.rename(columns={
        'Term': 't',
        'Adjusted P-value': 'p',
        'Odds Ratio': 'o',
        'Combined Score': 'c',
        'Genes': 'g'
    })

    print(results.to_json(orient='records'))

if __name__ == "__main__":
    main()
