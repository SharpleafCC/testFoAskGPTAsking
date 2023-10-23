
# Theme Asset Management Guide
One of the unique features of our theme is its dynamic approach to enqueuing assets (like CSS and JS files) based on the specific content being viewed. This ensures that only the necessary assets are loaded, improving site performance. Here's a quick guide on how this system works:

#### 1. Folder Structure:
Our theme's assets are organized into a hierarchical folder structure:


<pre >/assets/
    /local/
        /css/
        /js/
</pre>
Within these main folders, we further categorize the assets:

- Taxonomies: Located under /taxonomies/. Assets specific to taxonomy terms (e.g., genre or location).

 - Custom Post Types (CPTs): Located under /cpts/. Every CPT has a folder named after it, and it houses assets specific to singular entries of that CPT. There are also generic singular and archive assets for each CPT.

 - Regular Pages: Located under /pages/. Assets specific to individual regular pages (e.g., About Us or Contact).

 - General Assets: Directly inside /css/ or /js/. These are for the homepage, front page, 404 page, etc.

####2. Asset Naming Convention:
The naming of the files is crucial as our dynamic system relies on it to load the correct assets:

Singular CPT Entries: If you have specific assets for a singular entry, name them after the entry's slug (e.g., specific-product-slug.css).

Generic CPT Assets: For general singular assets, use the format [cptname]-singular.css, and for archives, use [cptname]-archive.css.

Regular Pages: Simply name the asset after the page's slug (e.g., about-us.css).

#### 3. How Assets are Dynamically Loaded:
Based on the current page's context (whether it's a taxonomy, a singular post, an archive, a regular page, etc.), our theme checks the respective directory for an asset named appropriately:

If the specific asset exists (like for a particular product or news post), it gets enqueued.
If not, it falls back to the generic CPT asset.
If even that doesn't exist, it defaults to the general assets.
This all happens via the _scripts.php_ `load_scripts()` function.
#### 4. Adding New Assets:
When you create a new CPT, taxonomy, or page:

Make sure to place the assets in the correct directory following the aforementioned structure.
Name the assets appropriately.
The system will handle the rest!
#### 5. Note for Maintenance:
This dynamic approach means there's less manual enqueuing of scripts and styles in the code. However, be mindful of the folder structure and naming conventions, as the system heavily relies on them. If you encounter an asset not loading, always check the file's location and name first.

### Expected File structure for SCSS and JS
<pre>
/assets/
    /local/
        /sass/
            /taxonomies/
                genre.css
                location.css
                ...
            /cpts/
                /product/
                    specific-product-slug.css
                    another-product-slug.css
                    ...
                product-singular.css
                product-archive.css
                ...
                /news/
                    specific-news-slug.css
                    ...
                news-singular.css
                news-archive.css
                ...
            /pages/
                home.css
                about-us.css
                contact.css
                ...
            404.css
            ...
        /js/
            /taxonomies/
                genre.js
                location.js
                ...
            /cpts/
                /product/
                    specific-product-slug.js
                    another-product-slug.js
                    ...
                product-singular.js
                product-archive.js
                ...
                /news/
                    specific-news-slug.js
                    ...
                news-singular.js
                news-archive.js
                ...
            /pages/
                about-us.js
                contact.js
                ...
            home.js
            front-page.js
            404.js
            ...
</pre>