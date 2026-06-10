<?php
require_once '../includes/config.php';
$pageTitle    = 'Products';
$pageDesc     = 'Browse Droga Pharma\'s comprehensive product catalog – pharmaceuticals, medical devices, diagnostics, laboratory equipment, surgical supplies, and orthopedic solutions.';
$pageKeywords = 'pharmaceutical products Ethiopia, medical devices, diagnostics, laboratory equipment, surgical supplies';
require_once '../includes/header.php';

$db = getDB();
$cat = sanitize($_GET['cat'] ?? 'all');
$search = sanitize($_GET['search'] ?? '');

// Fetch products
$sql = "SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p
        LEFT JOIN product_categories c ON p.category_id = c.id
        WHERE p.is_active = 1";
if ($cat !== 'all') $sql .= " AND c.slug = '" . $db->real_escape_string($cat) . "'";
if ($search) $sql .= " AND (p.name LIKE '%" . $db->real_escape_string($search) . "%' OR p.short_description LIKE '%" . $db->real_escape_string($search) . "%')";
$sql .= " ORDER BY p.sort_order ASC, p.name ASC";
$productsResult = $db->query($sql);
$products = $productsResult ? $productsResult->fetch_all(MYSQLI_ASSOC) : [];

// Fetch categories
$catsResult = $db->query("SELECT * FROM product_categories WHERE is_active=1 ORDER BY sort_order ASC");
$categories = $catsResult ? $catsResult->fetch_all(MYSQLI_ASSOC) : [];
?>

<!-- Page Hero -->
<section class="page-hero" style="background-image: url('../assets/images/products-hero.jpg')">
  <div class="page-hero-overlay"></div>
  <div class="container page-hero-content">
    <div data-aos="fade-up">
      <span class="hero-badge"><i class="fas fa-th-large"></i> Products</span>
      <h1 class="text-white mt-3">Product Catalog</h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb-custom">
          <li><a href="<?= SITE_URL ?>">Home</a></li>
          <li class="separator"><i class="fas fa-chevron-right"></i></li>
          <li class="active">Products</li>
        </ol>
      </nav>
    </div>
  </div>
</section>

<!-- Products Section -->
<section class="products-section">
  <div class="container">

    <!-- Search + Filter Bar -->
    <div class="products-toolbar" data-aos="fade-up">
      <div class="products-search">
        <i class="fas fa-search"></i>
        <input type="text" id="productSearch" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>" aria-label="Search products">
      </div>
      <div class="products-filter-tabs">
        <button class="filter-btn <?= $cat === 'all' ? 'active' : '' ?>" data-filter="all">All Products</button>
        <?php if (!empty($categories)): ?>
          <?php foreach ($categories as $c): ?>
          <button class="filter-btn <?= $cat === $c['slug'] ? 'active' : '' ?>" data-filter="<?= htmlspecialchars($c['slug']) ?>">
            <?= htmlspecialchars($c['name']) ?>
          </button>
          <?php endforeach; ?>
        <?php else: ?>
          <?php $demoCats = [
            ['slug'=>'pharmaceuticals','name'=>'Pharmaceuticals'],
            ['slug'=>'medical-devices','name'=>'Medical Devices'],
            ['slug'=>'diagnostics',    'name'=>'Diagnostics'],
            ['slug'=>'laboratory',     'name'=>'Laboratory'],
            ['slug'=>'surgical',       'name'=>'Surgical'],
            ['slug'=>'orthopedic',     'name'=>'Orthopedic'],
          ];
          foreach ($demoCats as $c): ?>
          <button class="filter-btn <?= $cat === $c['slug'] ? 'active' : '' ?>" data-filter="<?= $c['slug'] ?>">
            <?= $c['name'] ?>
          </button>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Products Grid -->
    <div class="row g-4" id="productsGrid">
      <?php if (!empty($products)): ?>
        <?php foreach ($products as $i => $product): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 product-card-wrap" data-aos="fade-up" data-aos-delay="<?= ($i % 4) * 60 ?>">
          <div class="product-card" data-category="<?= htmlspecialchars($product['category_slug']) ?>">
            <div class="product-image">
              <img src="<?= SITE_URL ?>/uploads/products/<?= htmlspecialchars($product['image']) ?>"
                   alt="<?= htmlspecialchars($product['name']) ?>" loading="lazy">
              <div class="product-overlay">
                <button class="btn btn-primary btn-sm" onclick="openProductModal(<?= $product['id'] ?>)">
                  <i class="fas fa-eye"></i> View Details
                </button>
              </div>
            </div>
            <div class="product-body">
              <span class="product-category-tag"><?= htmlspecialchars($product['category_name']) ?></span>
              <h5 class="product-name"><?= htmlspecialchars($product['name']) ?></h5>
              <p class="product-desc"><?= htmlspecialchars(substr($product['description'], 0, 80)) ?>...</p>
              <div class="product-actions">
                <button class="btn btn-outline btn-sm" onclick="openProductModal(<?= $product['id'] ?>)">
                  <i class="fas fa-info-circle"></i> Details
                </button>
                <a href="contact.php?product=<?= urlencode($product['name']) ?>" class="btn btn-primary btn-sm">
                  <i class="fas fa-envelope"></i> Inquire
                </a>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <!-- Demo Products -->
        <?php
        $demoProducts = [
          ['cat'=>'pharmaceuticals','name'=>'Amoxicillin 500mg Capsules',    'desc'=>'Broad-spectrum antibiotic for bacterial infections. Available in blister packs of 10, 20, and 100 capsules.'],
          ['cat'=>'pharmaceuticals','name'=>'Metformin 850mg Tablets',       'desc'=>'First-line medication for type 2 diabetes management. WHO Essential Medicine.'],
          ['cat'=>'pharmaceuticals','name'=>'Atorvastatin 20mg Tablets',     'desc'=>'Statin medication for cholesterol management and cardiovascular risk reduction.'],
          ['cat'=>'medical-devices','name'=>'Digital Blood Pressure Monitor', 'desc'=>'Automatic upper arm blood pressure monitor with irregular heartbeat detection.'],
          ['cat'=>'medical-devices','name'=>'Pulse Oximeter',                 'desc'=>'Fingertip pulse oximeter for SpO2 and pulse rate measurement. FDA cleared.'],
          ['cat'=>'diagnostics',   'name'=>'Rapid Malaria Test Kit',         'desc'=>'Rapid diagnostic test for Plasmodium falciparum and P. vivax detection.'],
          ['cat'=>'diagnostics',   'name'=>'Blood Glucose Meter',            'desc'=>'Accurate blood glucose monitoring system with 500-test memory and PC connectivity.'],
          ['cat'=>'laboratory',    'name'=>'Centrifuge Machine',             'desc'=>'Benchtop centrifuge with 12-place rotor, 4000 RPM max speed, digital display.'],
          ['cat'=>'laboratory',    'name'=>'Microscope Binocular',           'desc'=>'Binocular compound microscope with 4x, 10x, 40x, 100x objectives. LED illumination.'],
          ['cat'=>'surgical',      'name'=>'Surgical Gloves (Sterile)',      'desc'=>'Latex surgical gloves, powder-free, sterile. Available in sizes 6.0 to 9.0.'],
          ['cat'=>'surgical',      'name'=>'Disposable Syringes',            'desc'=>'3-part disposable syringes with needle. Available in 1ml, 2ml, 5ml, 10ml sizes.'],
          ['cat'=>'orthopedic',    'name'=>'Knee Replacement System',        'desc'=>'Total knee replacement implant system with tibial, femoral, and patellar components.'],
        ];
        foreach ($demoProducts as $i => $p): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 product-card-wrap" data-aos="fade-up" data-aos-delay="<?= ($i % 4) * 60 ?>">
          <div class="product-card" data-category="<?= $p['cat'] ?>">
            <div class="product-image product-image-placeholder">
              <div class="product-placeholder-icon">
                <i class="fas <?= $p['cat'] === 'pharmaceuticals' ? 'fa-pills' : ($p['cat'] === 'medical-devices' ? 'fa-stethoscope' : ($p['cat'] === 'diagnostics' ? 'fa-microscope' : ($p['cat'] === 'laboratory' ? 'fa-flask' : ($p['cat'] === 'surgical' ? 'fa-syringe' : 'fa-bone')))) ?>"></i>
              </div>
            </div>
            <div class="product-body">
              <span class="product-category-tag"><?= ucwords(str_replace('-', ' ', $p['cat'])) ?></span>
              <h5 class="product-name"><?= $p['name'] ?></h5>
              <p class="product-desc"><?= $p['desc'] ?></p>
              <div class="product-actions">
                <a href="contact.php" class="btn btn-outline btn-sm"><i class="fas fa-info-circle"></i> Details</a>
                <a href="contact.php" class="btn btn-primary btn-sm"><i class="fas fa-envelope"></i> Inquire</a>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Download Brochure CTA -->
    <div class="text-center mt-5 pt-3" data-aos="fade-up">
      <div class="brochure-cta">
        <i class="fas fa-file-pdf"></i>
        <div>
          <h5>Download Our Product Catalog</h5>
          <p>Get the complete list of our products with specifications and pricing</p>
        </div>
        <a href="../assets/downloads/droga-pharma-catalog.pdf" class="btn btn-primary" download>
          <i class="fas fa-download"></i> Download PDF
        </a>
      </div>
    </div>

  </div>
</section>

<!-- Request Info CTA -->
<section class="cta-section bg-gradient">
  <div class="container">
    <div class="cta-inner" data-aos="zoom-in">
      <div class="cta-icon"><i class="fas fa-box-open"></i></div>
      <h2 class="cta-title text-white">Can't Find What You Need?</h2>
      <p class="cta-subtitle">Our team can source specific pharmaceutical and medical products on request. Contact us with your requirements.</p>
      <div class="cta-buttons">
        <a href="contact.php" class="btn btn-outline-white btn-lg"><i class="fas fa-envelope"></i> Request a Product</a>
      </div>
    </div>
  </div>
</section>

<?php require_once '../includes/footer.php'; ?>
