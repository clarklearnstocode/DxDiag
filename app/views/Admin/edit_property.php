<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook Admin | Edit Property</title>
    <link rel="stylesheet" href="assets/css/admin.css">
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
            <p>Updating: <strong style="color:var(--primary);"><?php echo htmlspecialchars($property['Property_Name']); ?></strong></p>
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
            <div style="margin-bottom:14px;">
                <img src="assets/img/<?php echo htmlspecialchars($property['image_path']); ?>"
                     id="currentImgEl" alt="Current"
                     style="width:100%;max-height:220px;object-fit:cover;border-radius:11px;border:1px solid var(--border);">
                <p style="font-size:0.72rem;color:var(--text-muted);margin-top:7px;">Current image — upload a new one below to replace it</p>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label">Replace Image (optional)</label>
                <div class="upload-zone" id="uploadZone">
                    <input type="file" name="property_image" accept="image/jpeg,image/png,image/webp" onchange="previewImage(this)">
                    <div class="upload-text">Drop new image here or <strong>click to browse</strong></div>
                    <div class="upload-hint">Leave blank to keep current image</div>
                </div>
                <div id="new-preview-wrap" style="display:none;margin-top:10px;">
                    <img id="new-img-preview" src="" alt="" style="width:100%;max-height:180px;object-fit:cover;border-radius:10px;border:1px solid var(--border);">
                    <p style="font-size:0.73rem;color:var(--success);margin-top:6px;">✓ New image ready — will replace current on save</p>
                </div>
            </div>

            <span class="form-section-title">Description</span>

            <div class="form-group">
                <label class="form-label">Property Description *</label>
                <textarea name="description" required><?php echo htmlspecialchars($property['Property_Description'] ?? ''); ?></textarea>
            </div>

            <div style="display:flex;gap:12px;margin-top:14px;">
                <button type="submit" class="btn btn-primary btn-lg" style="flex:1;">✦ Save Changes</button>
                <a href="index.php?action=admin_dashboard" class="btn btn-ghost btn-lg">Cancel</a>
            </div>
        </div>

    </form>
</main>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('new-img-preview').src = e.target.result;
            document.getElementById('new-preview-wrap').style.display = 'block';
            document.getElementById('uploadZone').classList.add('has-file');
            var cur = document.getElementById('currentImgEl');
            if (cur) cur.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>
