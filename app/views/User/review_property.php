<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Write a Review | EstateBook</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/user-layout.css?v=1.0">
    <style>
        .review-property-card {
            display: flex; gap: 18px; align-items: center;
            padding: 18px 22px;
            background: linear-gradient(135deg, #001f3f 0%, #003060 100%);
            border-radius: 14px; margin-bottom: 28px;
            position: relative; overflow: hidden;
        }
        .review-property-card::before {
            content: ''; position: absolute; top: -30px; right: -30px;
            width: 120px; height: 120px; border-radius: 50%;
            background: rgba(205,170,86,0.08);
        }
        .rpc-img { width: 80px; height: 64px; border-radius: 10px; object-fit: cover; flex-shrink: 0; border: 2px solid rgba(205,170,86,0.4); }
        .rpc-body { flex: 1; }
        .rpc-eyebrow { font-size: 0.65rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #cdaa56; margin-bottom: 4px; }
        .rpc-name { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 4px; }
        .rpc-dates { font-size: 0.78rem; color: rgba(255,255,255,0.6); }
        .rpc-dates em { color: rgba(205,170,86,0.85); font-style: normal; }

        /* Star Picker */
        .star-picker-section { text-align: center; padding: 28px 0 24px; border-bottom: 1px solid #f0ebe0; margin-bottom: 24px; }
        .star-picker-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px; color: #6b7685; margin-bottom: 16px; display: block; }
        .star-picker { display: flex; gap: 8px; justify-content: center; flex-direction: row-reverse; }
        .star-picker input { display: none; }
        .star-picker label {
            font-size: 2.6rem; cursor: pointer; color: #ddd3c0;
            transition: color 0.1s, transform 0.12s;
            -webkit-user-select: none; user-select: none;
        }
        .star-picker label:hover,
        .star-picker label:hover ~ label,
        .star-picker input:checked ~ label {
            color: #cdaa56;
        }
        .star-picker label:hover { transform: scale(1.2); }
        .star-descriptor {
            margin-top: 12px; height: 22px;
            font-size: 0.88rem; font-weight: 600; color: #005f56;
            transition: opacity 0.2s;
        }

        /* Category Ratings */
        .category-ratings { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 24px; }
        @media(max-width:500px){ .category-ratings { grid-template-columns: 1fr; } }
        .cat-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
        .cat-label { font-size: 0.82rem; color: #4a5568; font-weight: 500; }
        .cat-stars { display: flex; gap: 3px; }
        .cat-star {
            font-size: 1.15rem; cursor: pointer; color: #ddd3c0;
            transition: color 0.1s;
            line-height: 1;
        }
        .cat-star.active { color: #cdaa56; }

        /* Comment box */
        .review-textarea {
            width: 100%; min-height: 130px; resize: vertical;
            border: 1.5px solid #e4dccb; border-radius: 10px;
            padding: 13px 16px; font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem; color: #1e2a3a; background: #fdfaf5;
            transition: border-color 0.18s, box-shadow 0.18s; outline: none;
            line-height: 1.65;
        }
        .review-textarea:focus { border-color: #005f56; box-shadow: 0 0 0 3px rgba(0,95,86,0.1); background: #fff; }
        .char-count { font-size: 0.72rem; color: #9fa8b3; text-align: right; margin-top: 6px; }
        .char-count.warn { color: #e67e22; }

        /* Tips */
        .review-tips { background: #f5faf9; border: 1px solid #a8dfcc; border-radius: 10px; padding: 14px 18px; margin-top: 18px; }
        .review-tips ul { list-style: none; padding: 0; margin: 0; }
        .review-tips li { font-size: 0.8rem; color: #2a5c49; padding: 3px 0; display: flex; gap: 8px; }
        .review-tips li::before { content: '✓'; color: #005f56; font-weight: 700; flex-shrink: 0; }

        .submit-row { margin-top: 28px; display: flex; gap: 12px; align-items: center; }

        /* Rating error */
        .rating-required-msg { display: none; color: #852020; font-size: 0.8rem; margin-top: 6px; }
        .rating-required-msg.show { display: block; }
    </style>
</head>
<body>
<?php $activePage = 'my_bookings'; require_once __DIR__ . '/_user_navbar.php'; ?>

<div class="eb-page eb-page-narrow">
    <div class="eb-page-header">
        <h1 class="page-title">Share Your Experience</h1>
        <p class="page-sub">Your honest review helps other guests choose their perfect estate.</p>
    </div>

    <!-- Property recap banner -->
    <div class="review-property-card">
        <img class="rpc-img"
             src="assets/img/<?php echo htmlspecialchars($property['image_path'] ?? 'villa1.png'); ?>"
             alt="<?php echo htmlspecialchars($property['Property_Name']); ?>"
             onerror="this.src='assets/img/villa1.png'">
        <div class="rpc-body">
            <div class="rpc-eyebrow">Reviewing Your Stay</div>
            <div class="rpc-name"><?php echo htmlspecialchars($property['Property_Name']); ?></div>
            <div class="rpc-dates">
                📅 <em><?php echo date('M d, Y', strtotime($booking['Check_In'])); ?></em>
                &nbsp;→&nbsp;
                <em><?php echo date('M d, Y', strtotime($booking['Check_Out'])); ?></em>
            </div>
        </div>
    </div>

    <form action="index.php?action=submit_review" method="POST" id="reviewForm">
        <input type="hidden" name="booking_id"  value="<?php echo (int)$booking['Booking_Id']; ?>">
        <input type="hidden" name="property_id" value="<?php echo (int)$property['Property_Id']; ?>">
        <input type="hidden" name="rating" id="ratingInput" value="">

        <div class="eb-card eb-card-padded">
            <!-- Overall Star Rating -->
            <div class="star-picker-section">
                <span class="star-picker-label">Overall Rating</span>
                <div class="star-picker" id="starPicker">
                    <input type="radio" name="_star5" id="s5" value="5"><label for="s5" title="Exceptional">★</label>
                    <input type="radio" name="_star4" id="s4" value="4"><label for="s4" title="Very Good">★</label>
                    <input type="radio" name="_star3" id="s3" value="3"><label for="s3" title="Good">★</label>
                    <input type="radio" name="_star2" id="s2" value="2"><label for="s2" title="Fair">★</label>
                    <input type="radio" name="_star1" id="s1" value="1"><label for="s1" title="Poor">★</label>
                </div>
                <div class="star-descriptor" id="starDescriptor"></div>
                <div class="rating-required-msg" id="ratingError">Please select a star rating before submitting.</div>
            </div>

            <!-- Category Sub-Ratings -->
            <span class="eb-section-label">Category Ratings <span style="font-weight:400;color:#9fa8b3;font-size:0.7rem;letter-spacing:0;text-transform:none">(optional)</span></span>
            <div class="category-ratings">
                <?php
                    $cats = [
                        ['key'=>'cleanliness', 'label'=>'🧹 Cleanliness'],
                        ['key'=>'comfort',     'label'=>'🛏 Comfort'],
                        ['key'=>'location',    'label'=>'📍 Location'],
                        ['key'=>'value',       'label'=>'💎 Value for Money'],
                    ];
                    foreach ($cats as $cat):
                ?>
                <div class="cat-row">
                    <span class="cat-label"><?php echo $cat['label']; ?></span>
                    <div class="cat-stars" data-cat="<?php echo $cat['key']; ?>">
                        <?php for ($i=1;$i<=5;$i++): ?>
                            <span class="cat-star" data-val="<?php echo $i; ?>">★</span>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="cat_<?php echo $cat['key']; ?>" class="cat-input" data-cat="<?php echo $cat['key']; ?>" value="0">
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Written Review -->
            <span class="eb-section-label" style="margin-top:8px">Your Review</span>
            <div class="eb-form-group" style="margin-bottom:0">
                <textarea
                    name="comment"
                    id="reviewText"
                    class="review-textarea"
                    placeholder="Describe your experience — what made this stay memorable, what could be improved, would you recommend it to others…"
                    maxlength="1200"
                    oninput="updateCharCount(this)"></textarea>
                <div class="char-count" id="charCount">0 / 1200</div>
            </div>

            <div class="review-tips">
                <ul>
                    <li>Focus on your personal experience, not general information about the area</li>
                    <li>Mention highlights: amenities, cleanliness, host responsiveness</li>
                    <li>Be specific and constructive if sharing any concerns</li>
                </ul>
            </div>
        </div>

        <div class="submit-row">
            <button type="submit" class="eb-btn eb-btn-gold" id="submitBtn">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                Publish Review
            </button>
            <a href="index.php?action=my_bookings" class="eb-btn eb-btn-ghost">Maybe Later</a>
        </div>
    </form>
</div>

<script>
/* ── Overall Star Picker ── */
const starDescriptions = { 1:'Poor', 2:'Fair', 3:'Good', 4:'Very Good', 5:'Exceptional ✨' };
let selectedRating = 0;

document.querySelectorAll('#starPicker label').forEach(label => {
    label.addEventListener('click', () => {
        const val = parseInt(label.title === 'Exceptional' ? 5
            : label.title === 'Very Good' ? 4
            : label.title === 'Good' ? 3
            : label.title === 'Fair' ? 2 : 1);
        selectedRating = parseInt(label.previousElementSibling.value || label.getAttribute('for').replace('s',''));
        document.getElementById('ratingInput').value = selectedRating;
        document.getElementById('starDescriptor').textContent = starDescriptions[selectedRating] || '';
        document.getElementById('ratingError').classList.remove('show');
    });
});

/* ── Category Sub-Ratings ── */
document.querySelectorAll('.cat-stars').forEach(group => {
    const stars = group.querySelectorAll('.cat-star');
    const cat   = group.dataset.cat;
    const input = document.querySelector(`.cat-input[data-cat="${cat}"]`);
    stars.forEach((star, idx) => {
        star.addEventListener('mouseover', () => stars.forEach((s,i) => s.classList.toggle('active', i <= idx)));
        star.addEventListener('mouseout',  () => {
            const v = parseInt(input.value || 0);
            stars.forEach((s,i) => s.classList.toggle('active', v > 0 && i < v));
        });
        star.addEventListener('click', () => {
            const v = idx + 1;
            input.value = v;
            stars.forEach((s,i) => s.classList.toggle('active', i < v));
        });
    });
});

/* ── Char counter ── */
function updateCharCount(el) {
    const count = el.value.length;
    const el2   = document.getElementById('charCount');
    el2.textContent = count + ' / 1200';
    el2.classList.toggle('warn', count > 1000);
}

/* ── Form validation ── */
document.getElementById('reviewForm').addEventListener('submit', function(e) {
    const rating = document.getElementById('ratingInput').value;
    if (!rating || rating === '0') {
        e.preventDefault();
        document.getElementById('ratingError').classList.add('show');
        document.getElementById('starPicker').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>
</body>
</html>
