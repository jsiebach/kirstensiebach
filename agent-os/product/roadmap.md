# Product Roadmap

1. [ ] Backend Migration to Laravel 12 - Upgrade Laravel framework from version 10 to 12, ensuring all dependencies are compatible and all existing functionality is preserved. Update configuration files, migrate deprecated methods, run full test suite to verify stability. `M`

2. [ ] Replace Nova with Filament 4 - Remove Laravel Nova 4.33 admin panel and implement Laravel Filament 4 as the new admin interface. Migrate all existing Nova resources (publications, research projects, lab members, settings) to Filament resources with equivalent functionality. Configure authentication and authorization. `L`

3. [ ] Block-Style Content Editor - Implement Filament-compatible rich block editor (FilamentTiptapEditor or similar) for page content management. Enable WYSIWYG editing with drag-and-drop blocks, heading styles, lists, links, and basic formatting. Create editable page model and migration. `L`

4. [ ] Image Upload and Management - Add image upload capabilities to block editor with drag-and-drop interface. Implement automatic image optimization and responsive image generation. Create media library interface in Filament for browsing and managing uploaded images. `M`

5. [ ] Resources Page with File Management - Create new Resources model, migration, and Filament resource for managing student materials. Build file upload system for documents/PDFs with organized storage. Add categorization by class/topic with filtering. Create public-facing resources page with category navigation and download links. `L`

6. [ ] Publication-Research Tagging System - Extend publications and research models with many-to-many relationship via pivot table. Add tag selection interface in Filament publication resource to associate with research projects. Update research project public page to query and display linked publications and conference abstracts with automatic filtering. `M`

7. [ ] Lab Life Section - Create new Lab Life model and migration for photo galleries and updates. Split existing Lab Members functionality into two sections: current team roster and lab activities/updates. Build Filament resource for managing lab life entries with multiple image uploads per entry. `M`

8. [ ] Instagram or Photo Gallery Integration - Evaluate and implement either: (a) Instagram API integration to embed recent posts, (b) Flickr album embedding, or (c) native photo gallery with lightbox. Add configuration in Filament settings for choosing integration method. Create public-facing lab life page displaying visual content in grid layout. `S`

9. [ ] Frontend Design System - Replace Bootstrap 3 styling with modern design system. Create custom CSS/SCSS with professional academic aesthetic. Design reusable Blade components for consistent UI patterns (cards, headers, navigation, footer). Update typography, color scheme, and spacing for contemporary professional appearance. `L`

10. [ ] Homepage Redesign - Redesign landing page with modern layout featuring hero section, featured research highlights, recent publications preview, and call-to-action for speaking engagements. Ensure responsive design across all breakpoints. Optimize for fast initial page load. `M`

11. [ ] Research Page Enhancement - Update research projects display with improved visual hierarchy and card-based layout. Integrate publication display under each research project using new tagging system. Add filtering and search capabilities for research areas. `M`

12. [ ] Publications Page Polish - Enhance publications list with better filtering (by year, type, research area), improved typography, and consistent formatting. Add search functionality. Ensure proper citation formatting and external link handling. `S`

> Notes
> - Each item represents end-to-end (frontend + backend) functional and testable feature
> - Order reflects technical dependencies: backend migration before Filament, Filament before content editor, content features before frontend redesign
> - Total estimated timeline: 12-16 weeks for full roadmap completion
> - Phase 1-4 (items 1-4) create foundation for content autonomy - highest priority
> - Phase 5-8 (items 5-8) add new features requested by stakeholders
> - Phase 9-12 (items 9-12) polish user-facing experience for professional appearance
