<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook Admin | Edit Property</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/admin-property-forms.css">
</head>
<body>
<?php $activePage = 'dashboard'; require __DIR__ . '/_sidebar.php'; ?>

<main class="main-admin">

    <a href="index.php?action=admin_dashboard" class="back-link">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Dashboard
    </a>

    <div class="page-header">
        <div class="page-header-left">
            <h1>Edit Property</h1>
            <p>Updating: <strong class="u-editing-name"><?php echo htmlspecialchars($property['Property_Name']); ?></strong></p>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">✓ Property updated successfully.</div>
    <?php endif; ?>

    <form action="index.php?action=edit_property" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="property_id"   value="<?php echo $property['Property_Id']; ?>">
        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($property['image_path'] ?? 'villa1.png'); ?>">

        <div class="form-card">
            <span class="form-section-title">Basic Information</span>

            <div class="form-group">
                <label class="form-label">Property Name *</label>
                <input type="text" name="property_name" value="<?php echo htmlspecialchars($property['Property_Name']); ?>" required>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Location *</label>
                    <input type="text" name="location" value="<?php echo htmlspecialchars($property['Property_location']); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Rate Per Night (₱) *</label>
                    <input type="number" name="rate" value="<?php echo $property['Property_rate']; ?>" min="0" step="0.01" required>
                </div>
            </div>

            <span class="form-section-title">Property Details</span>

            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label">Guest Capacity *</label>
                    <input type="number" name="capacity" value="<?php echo $property['Property_capacity']; ?>" min="1" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Bathrooms *</label>
                    <input type="number" name="bathrooms" value="<?php echo $property['Property_bathrooms']; ?>" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Size (m²) *</label>
                    <input type="number" name="size" value="<?php echo $property['Property_size']; ?>" min="0" required>
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status">
                        <option value="Available" <?php echo $property['Status'] === 'Available' ? 'selected' : ''; ?>>Available</option>
                        <option value="Occupied"  <?php echo $property['Status'] === 'Occupied'  ? 'selected' : ''; ?>>Occupied</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Amenities</label>
                    <label class="checkbox-row">
                        <input type="checkbox" name="has_pool" value="1"
                               <?php echo (!empty($property['Has_pool']) && $property['Has_pool'] != '0') ? 'checked' : ''; ?>>
                        <span class="checkbox-label">🏊 Swimming Pool Included</span>
                    </label>
                </div>
            </div>

            <span class="form-section-title">Property Image</span>

            <?php if (!empty($property['image_path'])): ?>
            <div class="u-preview-wrap">
                <img src="assets/img/<?php echo htmlspecialchars($property['image_path']); ?>"
                     id="currentImgEl" alt="Current"
                     class="u-current-image">
                <p class="u-current-image-note">Current image — upload a new one below to replace it</p>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label">Replace Image (optional)</label>
                <div class="upload-zone" id="uploadZone">
                    <input type="file" name="property_image" accept="image/jpeg,image/png,image/webp" onchange="previewImage(this)">
                    <div class="upload-text">Drop new image here or <strong>click to browse</strong></div>
                    <div class="upload-hint">Leave blank to keep current image</div>
                </div>
                <div id="new-preview-wrap" class="u-new-preview-wrap">
                    <img id="new-img-preview" src="" alt="" class="u-new-preview-img">
                    <p class="u-new-preview-note">✓ New image ready — will replace current on save</p>
                </div>
            </div>

            <span class="form-section-title">Description</span>

            <div class="form-group">
                <label class="form-label">Property Description *</label>
                <textarea name="description" required><?php echo htmlspecialchars($property['Property_Description'] ?? ''); ?></textarea>
            </div>

            <div class="u-actions-row">
                <button type="submit" class="btn btn-primary btn-lg u-flex-grow">✦ Save Changes</button>
                <a href="index.php?action=admin_dashboard" class="btn btn-ghost btn-lg">Cancel</a>
            </div>
        </div>

    </form>
</main>

<script src="assets/js/admin-property-form.js"></script>
</body>
</html>
