############################
#Purpose: gene enrichment analysis
#Author: Li Jinchen
#Date: 20250515
############################

#Offline databases are located in ./offline_enrich_db/

#Set the cache directory to the current directory
#Grant 777 permissions to the working directory
import os
# ⚠️Set the matplotlib cache directory to avoid permission errors; must be set before importing other libraries!!!
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

    # If it's a string (may be a JSON string when passed from PHP), convert it to a list
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

    # Clean, filter and rename the results
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





