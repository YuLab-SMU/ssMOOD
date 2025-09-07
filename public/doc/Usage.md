# Usage

---

## 1. Overview of Page Functions

### **Home**

The **Home** page provides a general overview of the database and highlights its core features.

---

### **Browse**

The **Browse** section enables users to explore datasets in two primary categories:

* **Single-cell datasets**
  Includes both integrated datasets (aggregated from multiple studies) and individual datasets.

* **Bulk RNA-seq datasets**
  Also includes both integrated and individual datasets, supporting both cross-study comparisons and single-study analyses.

This section is designed to provide intuitive access to raw and processed data, facilitating downstream analyses.

---

### **About**

The **About** section provides comprehensive background information:

* **Methods**
  Describes the experimental protocols and computational pipelines used for data generation, preprocessing, and integration.

* **Usage**
  Offers guidance on accessing and analyzing datasets, including search, filtering, and visualization features.

* **FAQ**
  Answers to frequently asked questions regarding data interpretation, usage, and troubleshooting.

* **Contact Us**
  Provides contact information for technical support, collaborative inquiries, and data contribution requests.

---

### **Download**

The **Download** section provides direct access to:

* **Raw datasets**
  Publicly available single-cell and bulk RNA-seq datasets for offline analysis.

* **Source code**
  The complete source code of the web application, enabling reproducibility, local deployment, and further customization.

---

## 2. Browse — Single-Cell List (also applies to the ST List page)

![bp](./doc/img/1.png "bp")

\[1]. **Mobile Navigation**
* The navigation bar is displayed only on mobile devices or when the viewport width is limited.

\[2]. **Language Switcher**
* Supports Simplified Chinese, Traditional Chinese, and English.

\[3]. **Integrated (Combined) Datasets**
* Click the corresponding button in the chart to access an integrated dataset.

\[4]. **Individual Datasets**
* Click the dataset ID to view details of a specific dataset.

\[5]. **Condition Filter**
* Filter datasets according to selected criteria.

---

## 3. Dataset Page

Taking the **single-cell integrated dataset page** as an example:
![bp](./doc/img/2.png "bp")

\[1]. **Included Datasets**

* Clicking on a dataset redirects to the corresponding dataset page.

   > *Note: Available only for integrated datasets.*

---

![bp](./doc/img/3.png "bp")

* For **ST datasets**, the page displays a **spatial location map**.
* For **single-cell datasets**, the page displays a **UMAP plot**.
* Interaction and usage are consistent across both types.

---

### **Interactive Components**

\[1]. **Point Size**
* Adjust the size of the points in the plot.

\[2]. **Download Button**
* Save the current plot as a PNG image.

\[3]. **Reset Button**
* Restore the default view after zooming or panning.

\[4]. **Interactive Plot**
* Use mouse dragging to zoom into a selected region.

\[5]. **Gene Search Box**

* Case-insensitive search.
* Enter a gene name and click **Search** to generate the corresponding expression plot.

\[6]. **Gene Expression Plot**

* Visualizes the relative expression levels of a selected gene across individual cells.
* A continuous color gradient is applied, where lighter shades denote lower expression and darker shades indicate higher expression.

\[7]. **Interactive Legend**

* Provides flexible selection and combination of specific cell clusters for customized visualization.
* The UMAP (or spatial coordinates) and the gene expression plot are dynamically linked, ensuring synchronized updates across views.




---

![bp](./doc/img/4.png "bp")

\[1]. **Cell Counts per Cluster Plot**

* Displays the number of cells in each cluster using a bar chart, providing an overview of cluster size distribution.

\[2]. **Heatmap of Cell Counts Across Expression Levels and Clusters**

* Visualizes the distribution of cell counts across varying expression levels and clusters using a heatmap, enabling comparative assessment of expression patterns among cell types.


---

![bp](./doc/img/5.png "bp")

\[1]. **Group Selector**

* Allows the selection of the differential analysis group of interest.
* Currently supports only *One Cell Type vs. Other Cell Types*.

\[2]. **Cell Cluster Selector**

* Enables the selection of a specific cell cluster for analysis.

\[3]. **LogFC Cutoff**

* Adjustable threshold for log fold change, ranging from **0 to 10**.

\[4]. **Adjusted p-value Cutoff**

* Adjustable significance threshold, ranging from **1×10⁻⁶ to 0.5**.

\[5]. **LogFC Direction**

* Allows filtering of results by upregulated genes, downregulated genes, or all genes.

\[6]. **Filter**

* Enables case-insensitive search for specific genes.

\[7]. **Sorting**

* Clicking a column header sorts the results according to that column.

\[8]. **Download**

* Exports the differential analysis results as a CSV file.

\[9]. **Gene Enrichment Analysis**

* Performs real-time gene enrichment analysis based on the differential expression results.
* Enrichment analysis must be rerun if the differential expression results are updated.

---

![bp](./doc/img/6.png "bp")


\[1]. **Filter**

* Provides a case-insensitive search function for pathway names.

\[2]. **Gene Links**

* Clicking a gene name redirects to the corresponding external database for detailed information.

\[3]. **Download**

* Exports the enrichment analysis results as a CSV file for offline use.

