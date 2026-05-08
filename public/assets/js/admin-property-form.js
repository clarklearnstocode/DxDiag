(() => {
    function previewImage(input) {
        if (!(input.files && input.files[0])) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            const newPreview = document.getElementById('new-img-preview');
            const newPreviewWrap = document.getElementById('new-preview-wrap');
            const addPreview = document.getElementById('img-preview');
            const addPreviewWrap = document.getElementById('preview-wrap');
            const uploadZone = document.getElementById('uploadZone');
            const currentImg = document.getElementById('currentImgEl');

            if (newPreview && newPreviewWrap) {
                newPreview.src = e.target.result;
                newPreviewWrap.style.display = 'block';
            }
            if (addPreview && addPreviewWrap) {
                addPreview.src = e.target.result;
                addPreviewWrap.style.display = 'block';
            }
            if (uploadZone) uploadZone.classList.add('has-file');
            if (currentImg) currentImg.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }

    window.previewImage = previewImage;
})();
