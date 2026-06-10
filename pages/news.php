<?php
require_once '../includes/config.php';
$pageTitle = 'News & Updates';
$pageDesc  = 'Latest news, updates, and insights from Droga Pharma PLC – pharmaceutical industry news, company announcements, and healthcare developments.';
require_once '../includes/header.php';

$db = getDB();
$slug = sanitize($_GET['slug'] ?? '');
$catFilter = sanitize($_GET['category'] ?? '');
$searchQ = sanitize($_GET['search'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 9;
$offset = ($page - 1) * $perPage;

if ($slug) {
  // Single article view
  $stmt = $db->prepare("SELECT n.*, u.name as author_name FROM news n LEFT JOIN admin_users u ON n.author_id = u.id WHERE n.slug = ? AND n.is_published = 1");
  $stmt->bind_param('s', $slug);
  $stmt->execute();
  $article = $stmt->get_result()->fetch_assoc();
  if ($article) {
    $db->query("UPDATE news SET views = views + 1 WHERE slug = '" . $db->real_escape_string($slug) . "'");
  }
} else {
  // Listing
  $where = "WHERE is_published=1";
  if ($catFilter) $where .= " AND category = '" . $db->real_escape_string($catFilter) . "'";
  if ($searchQ) $where .= " AND (title LIKE '%" . $db->real_escape_string($searchQ) . "%' OR excerpt LIKE '%" . $db->real_escape_string($searchQ) . "%')";
  $total = $db->query("SELECT COUNT(*) FROM news $where")->fetch_row()[0] ?? 0;
  $newsResult = $db->query("SELECT * FROM news $where ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
  $articles = $newsResult ? $newsResult->fetch_all(MYSQLI_ASSOC) : [];
  $totalPages = ceil($total / $perPage);
  $catsResult = $db->query("SELECT DISTINCT category FROM news WHERE is_published=1 ORDER BY category");
  $newsCategories = $catsResult ? array_column($catsResult->fetch_all(MYSQLI_ASSOC), 'category') : [];
}
?>

<section class="page-hero" style="background-image: url('../assets/images/news-hero.jpg')">
  <div class="page-hero-overlay"></div>
  <div class="container page-hero-content">
    <div data-aos="fade-up">
      <span class="hero-badge"><i class="fas fa-newspaper"></i> News</span>
      <h1 class="text-white mt-3"><?= $slug && isset($article) ? htmlspecialchars($article['title']) : 'News & Updates' ?></h1>
      <ol class="breadcrumb-custom">
        <li><a href="<?= SITE_URL ?>">Home</a></li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <?php if ($slug): ?>
        <li><a href="news.php">News</a></li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="active">Article</li>
        <?php else: ?>
        <li class="active">News</li>
        <?php endif; ?>
      </ol>
    </div>
  </div>
</section>

<?php if ($slug && isset($article)): ?>
<!-- ─── Single Article ─────────────────────────────────────────────────────── -->
<section class="article-section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8">
        <article class="article-body" data-aos="fade-up">
          <?php if ($article['image']): ?>
          <img src="<?= SITE_URL ?>/uploads/news/<?= htmlspecialchars($article['image']) ?>"
               alt="<?= htmlspecialchars($article['title']) ?>" class="article-hero-img" loading="lazy">
          <?php endif; ?>
          <div class="article-meta">
            <span class="news-category"><?= htmlspecialchars($article['category']) ?></span>
            <span><i class="fas fa-calendar"></i> <?= date('F d, Y', strtotime($article['created_at'])) ?></span>
            <span><i class="fas fa-user"></i> <?= htmlspecialchars($article['author_name'] ?? 'Droga Pharma') ?></span>
            <span><i class="fas fa-eye"></i> <?= number_format($article['views']) ?> views</span>
          </div>
          <h1 class="article-title"><?= htmlspecialchars($article['title']) ?></h1>
          <div class="article-content"><?= $article['content'] ?></div>
          <div class="article-share">
            <strong>Share:</strong>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode(SITE_URL . '/pages/news.php?slug=' . $article['slug']) ?>" target="_blank" rel="noopener" aria-label="Share on LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            <a href="https://twitter.com/intent/tweet?url=<?= urlencode(SITE_URL . '/pages/news.php?slug=' . $article['slug']) ?>&text=<?= urlencode($article['title']) ?>" target="_blank" rel="noopener" aria-label="Share on Twitter"><i class="fab fa-twitter"></i></a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(SITE_URL . '/pages/news.php?slug=' . $article['slug']) ?>" target="_blank" rel="noopener" aria-label="Share on Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="https://wa.me/?text=<?= urlencode($article['title'] . ' ' . SITE_URL . '/pages/news.php?slug=' . $article['slug']) ?>" target="_blank" rel="noopener" aria-label="Share on WhatsApp"><i class="fab fa-whatsapp"></i></a>
          </div>
        </article>
      </div>
      <div class="col-lg-4">
        <div class="news-sidebar" data-aos="fade-left">
          <div class="sidebar-widget">
            <h5>Recent Articles</h5>
            <?php
            $recent = $db->query("SELECT id, title, slug, created_at, image FROM news WHERE is_published=1 AND slug != '" . $db->real_escape_string($slug) . "' ORDER BY created_at DESC LIMIT 5");
            $recentArticles = $recent ? $recent->fetch_all(MYSQLI_ASSOC) : [];
            foreach ($recentArticles as $r): ?>
            <a href="news.php?slug=<?= htmlspecialchars($r['slug']) ?>" class="sidebar-article">
              <div class="sidebar-article-img">
                <?php if ($r['image']): ?>
                <img src="<?= SITE_URL ?>/uploads/news/<?= htmlspecialchars($r['image']) ?>" alt="<?= htmlspecialchars($r['title']) ?>" loading="lazy">
                <?php else: ?>
                <div class="sidebar-img-placeholder"><i class="fas fa-newspaper"></i></div>
                <?php endif; ?>
              </div>
              <div>
                <p><?= htmlspecialchars($r['title']) ?></p>
                <small><?= date('M d, Y', strtotime($r['created_at'])) ?></small>
              </div>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php else: ?>
<!-- ─── News Listing ───────────────────────────────────────────────────────── -->
<section class="news-listing-section">
  <div class="container">

    <!-- Search + Filter -->
    <div class="news-toolbar" data-aos="fade-up">
      <form method="GET" class="news-search-form">
        <div class="news-search-input">
          <i class="fas fa-search"></i>
          <input type="text" name="search" placeholder="Search news..." value="<?= htmlspecialchars($searchQ) ?>" aria-label="Search news">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
      </form>
      <div class="news-category-filters">
        <a href="news.php" class="filter-btn <?= !$catFilter ? 'active' : '' ?>">All</a>
        <?php foreach ($newsCategories as $nc): ?>
        <a href="news.php?category=<?= urlencode($nc) ?>" class="filter-btn <?= $catFilter === $nc ? 'active' : '' ?>"><?= htmlspecialchars($nc) ?></a>
        <?php endforeach; ?>
        <?php if (empty($newsCategories)): ?>
          <?php foreach (['Company News','Partnership','Innovation','Healthcare','Research'] as $nc): ?>
          <a href="news.php?category=<?= urlencode($nc) ?>" class="filter-btn <?= $catFilter === $nc ? 'active' : '' ?>"><?= $nc ?></a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="row g-4">
      <?php if (!empty($articles)): ?>
        <?php foreach ($articles as $i => $a): ?>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 80 ?>">
          <article class="news-card">
            <div class="news-image">
              <?php if ($a['image']): ?>
              <img src="<?= SITE_URL ?>/uploads/news/<?= htmlspecialchars($a['image']) ?>" alt="<?= htmlspecialchars($a['title']) ?>" loading="lazy">
              <?php else: ?>
              <div class="news-image-placeholder"><div class="news-placeholder-bg"></div></div>
              <?php endif; ?>
              <span class="news-category"><?= htmlspecialchars($a['category']) ?></span>
            </div>
            <div class="news-body">
              <div class="news-meta">
                <span><i class="fas fa-calendar"></i> <?= date('M d, Y', strtotime($a['created_at'])) ?></span>
                <span><i class="fas fa-eye"></i> <?= number_format($a['views'] ?? 0) ?></span>
              </div>
              <h4 class="news-title"><a href="news.php?slug=<?= htmlspecialchars($a['slug']) ?>"><?= htmlspecialchars($a['title']) ?></a></h4>
              <p class="news-excerpt"><?= htmlspecialchars($a['excerpt']) ?></p>
              <a href="news.php?slug=<?= htmlspecialchars($a['slug']) ?>" class="news-read-more">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
          </article>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <?php
        $demoArticles = [
          ['cat'=>'Company News','date'=>'May 20, 2025','title'=>'Droga Pharma Expands Distribution Network to Southern Ethiopia','excerpt'=>'We are proud to announce the expansion of our distribution network, now reaching all zones of Southern Ethiopia with faster delivery times and improved cold chain management.'],
          ['cat'=>'Partnership','date'=>'May 10, 2025','title'=>'New Partnership with Siemens Healthineers for Advanced Diagnostics','excerpt'=>'Droga Pharma signs a landmark agreement with Siemens Healthineers to bring cutting-edge diagnostic equipment to Ethiopian hospitals and clinics.'],
          ['cat'=>'Innovation','date'=>'Apr 28, 2025','title'=>'Droga Pharma Launches Digital Health Platform for Supply Chain','excerpt'=>'Our new digital platform enables real-time tracking of pharmaceutical supplies, improving efficiency and reducing stockouts across healthcare facilities.'],
          ['cat'=>'Healthcare','date'=>'Apr 15, 2025','title'=>'Free Medical Camp Serves 500 Patients in Rural Oromia','excerpt'=>'As part of our CSR commitment, Droga Pharma organized a free medical camp providing essential medicines and health screenings to rural communities.'],
          ['cat'=>'Research','date'=>'Apr 5, 2025','title'=>'Droga Pharma Funds Malaria Research at Addis Ababa University','excerpt'=>'A new research grant supports malaria drug resistance studies at AAU, contributing to Ethiopia\'s fight against one of its most prevalent diseases.'],
          ['cat'=>'Company News','date'=>'Mar 20, 2025','title'=>'Droga Pharma Achieves ISO 9001:2015 Recertification','excerpt'=>'We are proud to announce the successful recertification of our quality management system to ISO 9001:2015 standards, reaffirming our commitment to excellence.'],
        ];
        foreach ($demoArticles as $i => $a): ?>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 80 ?>">
          <article class="news-card">
            <div class="news-image news-image-placeholder"><div class="news-placeholder-bg"></div>
              <span class="news-category"><?= $a['cat'] ?></span>
            </div>
            <div class="news-body">
              <div class="news-meta"><span><i class="fas fa-calendar"></i> <?= $a['date'] ?></span></div>
              <h4 class="news-title"><a href="news.php"><?= $a['title'] ?></a></h4>
              <p class="news-excerpt"><?= $a['excerpt'] ?></p>
              <a href="news.php" class="news-read-more">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
          </article>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if (isset($totalPages) && $totalPages > 1): ?>
    <div class="pagination-wrap" data-aos="fade-up">
      <?php for ($p = 1; $p <= $totalPages; $p++): ?>
      <a href="?page=<?= $p ?><?= $catFilter ? '&category=' . urlencode($catFilter) : '' ?><?= $searchQ ? '&search=' . urlencode($searchQ) : '' ?>"
         class="page-btn <?= $p === $page ? 'active' : '' ?>"><?= $p ?></a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>

  </div>
</section>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>
