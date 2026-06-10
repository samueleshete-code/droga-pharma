<?php
require_once '../includes/config.php';
requireAdmin();
$db = getDB();
$pageTitle = 'Site Settings';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = ['site_name','site_tagline','site_email','site_phone','site_address',
               'facebook_url','twitter_url','linkedin_url','youtube_url',
               'years_experience','products_distributed','partner_hospitals','regions_served'];
    foreach ($fields as $key) {
        $val = sanitize($_POST[$key] ?? '');
        $stmt = $db->prepare("INSERT INTO site_settings (setting_key,setting_value) VALUES (?,?) ON DUPLICATE KEY UPDATE setting_value=?");
        $stmt->bind_param('sss', $key, $val, $val);
        $stmt->execute();
    }
    header('Location: settings.php?success=Settings+saved'); exit;
}

$settings = [];
$r = $db->query("SELECT setting_key,setting_value FROM site_settings");
if ($r) while ($row = $r->fetch_assoc()) $settings[$row['setting_key']] = $row['setting_value'];

function sv($settings, $key, $default='') {
    return htmlspecialchars($settings[$key] ?? $default);
}

require_once 'includes/admin_header.php';
?>

<form method="POST">
<div class="row g-4">

  <!-- General -->
  <div class="col-lg-6">
    <div class="admin-card">
      <div class="admin-card-header"><h5><i class="fas fa-globe me-2"></i>General Settings</h5></div>
      <div class="admin-card-body p-4">
        <div class="row g-3">
          <div class="col-12"><label class="form-label-admin">Site Name</label><input type="text" name="site_name" class="form-control-admin" value="<?= sv($settings,'site_name','Droga Pharma PLC') ?>"></div>
          <div class="col-12"><label class="form-label-admin">Tagline</label><input type="text" name="site_tagline" class="form-control-admin" value="<?= sv($settings,'site_tagline','Transforming Healthcare Across Africa') ?>"></div>
          <div class="col-12"><label class="form-label-admin">Contact Email</label><input type="email" name="site_email" class="form-control-admin" value="<?= sv($settings,'site_email','info@drogapharma.com') ?>"></div>
          <div class="col-12"><label class="form-label-admin">Phone</label><input type="text" name="site_phone" class="form-control-admin" value="<?= sv($settings,'site_phone','+251 11 123 4567') ?>"></div>
          <div class="col-12"><label class="form-label-admin">Address</label><input type="text" name="site_address" class="form-control-admin" value="<?= sv($settings,'site_address','Bole Road, Addis Ababa, Ethiopia') ?>"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Stats -->
  <div class="col-lg-6">
    <div class="admin-card">
      <div class="admin-card-header"><h5><i class="fas fa-chart-bar me-2"></i>Homepage Statistics</h5></div>
      <div class="admin-card-body p-4">
        <div class="row g-3">
          <div class="col-6"><label class="form-label-admin">Years of Experience</label><input type="text" name="years_experience" class="form-control-admin" value="<?= sv($settings,'years_experience','25+') ?>"></div>
          <div class="col-6"><label class="form-label-admin">Products Distributed</label><input type="text" name="products_distributed" class="form-control-admin" value="<?= sv($settings,'products_distributed','5000+') ?>"></div>
          <div class="col-6"><label class="form-label-admin">Partner Hospitals</label><input type="text" name="partner_hospitals" class="form-control-admin" value="<?= sv($settings,'partner_hospitals','500+') ?>"></div>
          <div class="col-6"><label class="form-label-admin">Regions Served</label><input type="text" name="regions_served" class="form-control-admin" value="<?= sv($settings,'regions_served','11+') ?>"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Social -->
  <div class="col-12">
    <div class="admin-card">
      <div class="admin-card-header"><h5><i class="fas fa-share-alt me-2"></i>Social Media Links</h5></div>
      <div class="admin-card-body p-4">
        <div class="row g-3">
          <div class="col-md-3"><label class="form-label-admin"><i class="fab fa-facebook me-1"></i>Facebook</label><input type="url" name="facebook_url" class="form-control-admin" value="<?= sv($settings,'facebook_url') ?>"></div>
          <div class="col-md-3"><label class="form-label-admin"><i class="fab fa-twitter me-1"></i>Twitter</label><input type="url" name="twitter_url" class="form-control-admin" value="<?= sv($settings,'twitter_url') ?>"></div>
          <div class="col-md-3"><label class="form-label-admin"><i class="fab fa-linkedin me-1"></i>LinkedIn</label><input type="url" name="linkedin_url" class="form-control-admin" value="<?= sv($settings,'linkedin_url') ?>"></div>
          <div class="col-md-3"><label class="form-label-admin"><i class="fab fa-youtube me-1"></i>YouTube</label><input type="url" name="youtube_url" class="form-control-admin" value="<?= sv($settings,'youtube_url') ?>"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12">
    <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-2"></i>Save All Settings</button>
  </div>
</div>
</form>

<?php require_once 'includes/admin_footer.php'; ?>
