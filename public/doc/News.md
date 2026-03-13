# News

## Version Mapping

The table below shows the correspondence between the **running version** (the user-facing release number) and the underlying **codebase version** (the core implementation).
This mapping helps ensure reproducibility in research and makes it easier to track which codebase was used for a specific software release.

```markdown
| Running Version | Codebase Version | Release Date | Notes                |
| --------------- | ---------------- | ------------ | -------------------- |
| v1.1.1 (Now)    | v4.8.0           | 2026-03-07   | Latest release       |
| v1.1.0          | v4.7.0           | 2025-08-27   | Second release       |
| v1.0.0          | v4.6.0           | 2025-07-06   | First major release  |

```

* **Running Version**: the version number displayed in the software interface or release notes.
* **Codebase Version**: the internal development version of the core analysis engine.
* **Notes**: brief description of the release status.

---
## V1.1.1
This release includes interface optimizations and several bug fixes to enhance stability and user experience.

* **UI Enhancement**: Optimized the data display interfaces for single-cell and transcriptomics data, as well as integrated datasets, introducing synchronized interactions between charts during data exploration.
Updated the download links on the download page.
* **Bug Fixes**:
Resolved an error that occurred when clicking on (0‑day) data within the spatial transcriptomics integrated dataset view.
Fixed a rendering failure issue when opening the Methods page.
Addressed an error in fetching request data for spatial transcriptomics.

---
## V1.1.0

This version focuses on improving stability, usability, and accessibility of the platform.

* **Module optimization**: Refined the display and dynamic responsiveness of the *Differential Expression (DE)* and *Gene Set Enrichment* modules.
* **Heatmap robustness**: Enhanced array boundary protection to improve stability and prevent rendering errors.
* **Language support**: Improved English translations and added automatic language selection based on the browser’s default settings.
* **Documentation**: Integrated on-site documentation to guide users through key functionalities.

---

## V1.0.0

This release introduced a series of major improvements and new features, with emphasis on performance, usability, and analytical capabilities.

* **Consistent visualization**: Chart color schemes aligned with those in the corresponding publication for result consistency.
* **Differential expression analysis**: Added sorting functionality for clearer interpretation of results.
* **Enhanced navigation**: Implemented an advanced pagination component to improve browsing efficiency within the DE and Gene Set Enrichment results.
* **Interactive legends**: Redesigned legends enabling synchronized interaction between UMAP/ST spatial plots and gene expression maps.
* **Functional enrichment**: Completed three Gene Ontology (GO) enrichment analyses.
* **Improved user experience**: Introduced a loading screen and animated chart placeholders to avoid blank states during data processing.
* **Performance optimization**: Improved chart rendering efficiency and refined heatmap visualization for enhanced clarity.
* **Code maintenance**: Resolved multiple warning messages to ensure cleaner builds and more stable performance.
* **Dataset integration**: Finalized the dataset integration module, with spatial transcriptomics (ST) datasets displayed in separate views.




