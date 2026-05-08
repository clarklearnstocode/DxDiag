<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook Admin | Add Property</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/admin-property-forms.css">
</head>
<body>
<?php $activePage = 'add_property'; require __DIR__ . '/_sidebar.php'; ?>

<main class="main-admin">

    <div class="page-header">
        <div class="page-header-left">
            <h1>List a New Property</h1>
            <p>Fill in the details to add a new estate to your collection.</p>
        </div>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">⚠ Something went wrong. Please check all fields and try again.</div>
    <?php endif; ?>

    <form action="index.php?action=add_property" method="POST" enctype="multipart/form-data">

        <div class="form-card">
            <span class="form-section-title">Basic Information</span>

            <div class="form-group">
                <label class="form-label">Property Name *</label>
                <input type="text" name="property_name" placeholder="e.g. Modern Glass Villa" required>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Location *</label>
                    <input type="text" name="location" placeholder="e.g. Bacolod City" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Rate Per Night (₱) *</label>
                    <input type="number" name="rate" placeholder="0" min="0" step="0.01" required>
                </div>
            </div>

            <span class="form-section-title">Property Details</span>

            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label">Guest Capacity *</label>
                    <input type="number" name="capacity" placeholder="0" min="1" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Bathrooms *</label>
                    <input type="number" name="bathrooms" placeholder="0" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Size (m²) *</label>
                    <input type="number" name="size" placeholder="0" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Amenities</label>
                <label class="checkbox-row">
                    <input type="checkbox" name="has_pool" value="1">
                    <span class="checkbox-label">🏊 Swimming Pool Included</span>
                </label>
            </div>

            <span class="form-section-title">Featured Image *</span>

            <div class="form-group">
                <div class="upload-zone" id="uploadZone">
                    <input type="file" name="property_image" id="imageInput"
                           accept="image/jpeg,image/png,image/webp" required
                           onchange="previewImage(this)">
                    <div class="upload-icon">🖼️</div>
                    <div class="upload-text">Drop image here or <strong>click to browse</strong></div>
                    <div class="upload-hint">JPG / PNG / WEBP — Recommended 1200×800px, max 5MB</div>
                </div>
                <div id="preview-wrap" class="u-add-preview-wrap">
                    <img id="img-preview" src="" alt="Preview"
                         class="u-add-preview-img">
                    <p class="u-add-preview-note">✓ Image selected</p>
                </div>
            </div>

            <span class="form-section-title">Description</span>

            <div class="form-group">
                <label class="form-label">Property Description *</label>
                <textarea name="description" placeholder="Describe the property's style, amenities, atmosphere..." required></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-full u-publish-btn">✦ Publish Property</button>
        </div>

    </form>

</main>

<script src="assets/js/admin-property-form.js"></script>
</body>
</html>
