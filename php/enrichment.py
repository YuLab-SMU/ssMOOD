############################
#功能：基因富集分析
#作者：李金晨
#时间：20250515
############################

#./offline_enrich_db/目录下为离线数据库

#配置缓存目录为当前目录
#请给予运行目录777权限
import os
script_dir = os.path.dirname(os.path.realpath(__file__))
cache_dir = os.path.join(script_dir, '.cache')
os.environ['MPLCONFIGDIR'] = cache_dir

import sys
import json
import pandas as pd
import gseapy as gp
def read_gmt(gmt_path):
    """读取本地GMT基因集文件"""
    gene_sets11 = {}
    with open(gmt_path) as f:
        for line in f:
            parts = line.strip().split('\t')
            term = parts[0]
            genes = parts[2:]
            gene_sets11[term] = genes
    return gene_sets11
    
def main():
    # 从标准输入读取JSON数据
    input_data = sys.stdin.read()
    data = json.loads(input_data)

    # 解析输入数据
    significant_genes = data['significant_genes']
    gene_sets_name = data['gene_sets']
    if isinstance(significant_genes, str):
        # 处理字符串格式的基因列表
        significant_genes = significant_genes.strip("[]").replace('"', '').split(',')

    # 配置缓存目录为当前目录
    script_dir = os.path.dirname(os.path.realpath(__file__))
    cache_dir = os.path.join(script_dir, '.cache')
    os.environ['MPLCONFIGDIR'] = cache_dir

    # 执行富集分析
    #gene_sets = read_gmt("./offline_enrich_db/m5.go.v2024.1.Mm.symbols.gmt")
    gene_sets_list = read_gmt("./offline_enrich_db/"+gene_sets_name)
     
    enrich_results = gp.enrich(
        gene_list=significant_genes,
        gene_sets=gene_sets_list,  # 使用输入的基因集
        verbose=False,
        outdir=None  # 不输出文件，直接返回结果
    )

    # 获取结果
    results = enrich_results.results
    results = results.drop(columns=['Gene_set'])
    results = results.drop(columns=['Overlap'])
    results = results.drop(columns=['P-value'])
    results = results[results['Adjusted P-value'] <= 0.05]  # 注意这里使用布尔索引
    results = results.reset_index(drop=True)          # 重置索引
    #results = results.sort_values('Adjusted P-value')  # 按显著性排序
    
    results = results.rename(columns={
        'Term': 't', 
        'Adjusted P-value': 'p',  
        'Odds Ratio': 'o',  
        'Combined Score': 'c',  
        'Genes': 'g',  
    })
    
    print(results.to_json(orient='records'))

if __name__ == "__main__":
    main()