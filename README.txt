IMPERIAL HORSES THEME
=====================

Requirements: WordPress 6+, PHP 7.4+, Advanced Custom Fields PRO (Repeater + Options Page).

Install
1. Install and activate ACF PRO.
2. Appearance > Themes > Add New > Upload Theme > imperial-horses.zip > Activate.
   On activation the theme creates the pages (Home, About us, Services, Blogs, Contact us),
   assigns their templates, sets Home as the front page and builds the main menu.
3. Custom Fields > Field Groups: if you see "Sync available", click Sync once.
   (The field groups are loaded from /acf-json automatically either way.)
4. Fill in "Site Settings" (left menu): logo, contact info, social, footer, CTA banner.
5. Open each page and fill its fields. Add posts (blog) and Projects from the dashboard.

Structure
- page-templates/  Home, About, Services, Blogs, Contact
- single.php       blog article          single-project.php / archive-project.php  projects
- header.php, footer.php   shared by every page (content from Site Settings)
- template-parts/  hero, cta-banner, post-card, project-card
- acf-json/        one field group per page + Site Settings + Post + Project
- inc/             helpers, defaults, icons, setup, acf, cpt, contact-form
- assets/          css and js
