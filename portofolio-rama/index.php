<?php
session_start();
require_once 'config.php';

// Fetch blog posts
try {
    $stmt = $pdo->prepare("SELECT bp.*, u.fullname as author FROM blog_posts bp LEFT JOIN users u ON bp.user_id = u.id ORDER BY bp.created_at DESC LIMIT 6");
    $stmt->execute();
    $blog_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $blog_posts = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rama Manansyah | Junior UI/UX Designer</title>
  <link rel="stylesheet" href="styles.css?v=<?=time()?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

  <!-- Header -->
  <header>
    <div class="container">
      <nav>
        <a href="#" class="logo">rama.manansyah</a>
        <div class="nav-links">
          <a href="#home">Beranda</a>
          <a href="#about">Tentang</a>
          <a href="#services">Layanan</a>
          <a href="#portfolio">Proyek</a>
          <a href="#blog">Blog</a>
          <a href="#contact">Kontak</a>
          <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
            <a href="admin-blog.php">Admin</a>
            <a href="logout.php">Logout</a>
          <?php else: ?>
            <a href="login.php">Login</a>
          <?php endif; ?>
        </div>
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section id="home" class="hero">
    <div class="container">
      <div class="hero-content">
        <span class="hero-badge">JUNIOR UI/UX DESIGNER</span>
        <h1>Membuat<br><span class="hero-highlight">Pengalaman Digital yang Menarik</span></h1>
        <p class="hero-text">Saya adalah junior UI/UX designer yang berdedikasi menciptakan antarmuka yang intuitif dan pengalaman pengguna yang luar biasa.</p>
        <div class="btn-group">
          <a href="#portfolio" class="btn btn-primary">Lihat Proyek Saya</a>
          <a href="#contact" class="btn btn-outline">Hubungi Saya</a>
        </div>
        <div class="stats">
          <div class="stat">
            <h3>5</h3>
            <p>Proyek Selesai</p>
          </div>
          <div class="stat">
            <h3>70%</h3>
            <p>guru senang dengan project</p>
          </div>
          <div class="stat">
            <h3>79%</h3>
            <p> Kepuasan guru</p>
          </div>
        </div>
      </div>
      <div class="hero-image">
        <div class="hero-image-container">
          <img src="Hero-image.jpg" />
          <div class="hero-floating-elements">
            <div class="floating-element element-1">
              <i class="fas fa-palette"></i>
            </div>
            <div class="floating-element element-2">
              <i class="fas fa-code"></i>
            </div>
            <div class="floating-element element-3">
              <i class="fas fa-lightbulb"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="about">
    <div class="container">
      <div class="about-content">
        <div class="about-img">
          <img src="About-image.jpg" />
        </div>
        <div class="about-text">
          <h2>TENTANG SAYA</h2>
          <h3>Mengubah Ide Menjadi Desain</h3>
          <p>Saya adalah junior UI/UX designer yang bersemangat menciptakan desain yang tidak hanya indah secara visual tetapi juga fungsional. Dengan fokus pada user-centered design, saya selalu berusaha memahami kebutuhan pengguna untuk menciptakan pengalaman terbaik.</p>
          <div class="skill-bar">
            <div class="skill-name">
              <span>UI Design</span>
              <span>90%</span>
            </div>
            <div class="skill-progress">
              <div class="skill-fill" data-width="90%"></div>
            </div>
          </div>
          <div class="skill-bar">
            <div class="skill-name">
              <span>UX Research</span>
              <span>85%</span>
            </div>
            <div class="skill-progress">
              <div class="skill-fill" data-width="85%"></div>
            </div>
          </div>
          <div class="skill-bar">
            <div class="skill-name">
              <span>Prototyping</span>
              <span>88%</span>
            </div>
            <div class="skill-progress">
              <div class="skill-fill" data-width="88%"></div>
            </div>
          </div>
          <div class="btn-group">
            <a href="#" class="btn btn-outline">Download CV</a>
            <a href="#" class="btn btn-primary">Bekerja Bersama</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section id="services" class="services">
    <div class="container">
      <h2>LAYANAN</h2>
      <h3>Apa yang Saya Tawarkan</h3>
      <div class="services-grid">
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-mobile-alt"></i>
          </div>
          <h3 class="service-title">UI Design</h3>
          <p class="service-desc">Membuat antarmuka yang menarik dan intuitif untuk aplikasi web dan mobile</p>
        </div>
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-search"></i>
          </div>
          <h3 class="service-title">UX Research</h3>
          <p class="service-desc">Melakukan penelitian mendalam untuk memahami kebutuhan dan perilaku pengguna</p>
        </div>
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-rocket"></i>
          </div>
          <h3 class="service-title">Prototyping</h3>
          <p class="service-desc">Membuat prototype interaktif untuk menguji konsep sebelum development</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Portfolio Section -->
<section id="portfolio" class="portfolio">
  <div class="container">
    <h2>PROYEK</h2>
    <h3>Portofolio Saya</h3>
    <div class="portfolio-filter">
      <button class="filter-btn active">Semua</button>
      <button class="filter-btn">Web Design</button>
      <button class="filter-btn">Mobile App</button>
    </div>

    <div class="portfolio-grid">
      <!-- Project 1 -->
      <div class="project-card">
        <div class="project-img-wrapper">
          <img src="Project-image.png" alt="Portfolio Website" class="project-img" />
        </div>
        <div class="project-info">
          <h3 class="project-title">Portfolio Website</h3>
          <p class="project-desc">Website portofolio profesional dengan design modern</p>
          <div class="project-actions">
            <a href="#" class="project-btn"><i class="fas fa-eye"></i></a>
            <a href="#" class="project-btn"><i class="fas fa-download"></i></a>
          </div>
        </div>
      </div>

      <!-- Project 2 -->
      <div class="project-card">
        <div class="project-img-wrapper">
          <img src="login.jpg" alt="Mobile App" class="project-img" />
        </div>
        <div class="project-info">
          <h3 class="project-title">Mobile App</h3>
          <p class="project-desc">Aplikasi student attendance system</p>
          <div class="project-actions">
            <a href="#" class="project-btn"><i class="fas fa-eye"></i></a>
            <a href="#" class="project-btn"><i class="fas fa-download"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

  <!-- Blog Section -->
  <section id="blog" class="blog">
    <div class="container">
      <h2>BLOG</h2>
      <h3>Artikel Seputar Programming</h3>
      <p class="blog-intro">Berbagi pengetahuan dan pengalaman seputar dunia programming, UI/UX design, dan teknologi terkini</p>
      
      <?php if (count($blog_posts) > 0): ?>
        <div class="blog-grid">
          <?php foreach ($blog_posts as $post): ?>
            <article class="blog-card">
              <?php if ($post['image']): ?>
                <div class="blog-image">
                  <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" />
                  <div class="blog-overlay">
                    <span class="blog-date">
                      <i class="fas fa-calendar"></i>
                      <?= date('d M Y', strtotime($post['created_at'])) ?>
                    </span>
                  </div>
                </div>
              <?php endif; ?>
              
              <div class="blog-content">
                <h4 class="blog-title"><?= htmlspecialchars($post['title']) ?></h4>
                <p class="blog-excerpt"><?= htmlspecialchars(substr($post['excerpt'], 0, 120)) ?>...</p>
                
                <div class="blog-meta">
                  <div class="blog-author">
                    <i class="fas fa-user"></i>
                    <span><?= htmlspecialchars($post['author'] ?? 'Admin') ?></span>
                  </div>
                  <a href="blog-detail.php?id=<?= $post['id'] ?>" class="blog-read-more">
                    Baca Selengkapnya
                    <i class="fas fa-arrow-right"></i>
                  </a>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
        
        <div class="blog-actions">
          <a href="blog.php" class="btn btn-outline">Lihat Semua Artikel</a>
          <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
            <a href="admin-blog.php" class="btn btn-primary">Kelola Blog</a>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div class="blog-empty">
          <div class="empty-icon">
            <i class="fas fa-newspaper"></i>
          </div>
          <h4>Belum Ada Artikel</h4>
          <p>Artikel blog sedang dalam persiapan. Nantikan konten menarik seputar programming!</p>
          <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
            <a href="admin-blog.php" class="btn btn-primary">Tambah Artikel Pertama</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Contact Section -->
  <section id="contact" class="contact">
    <div class="container">
      <div class="contact-container">
        <div class="contact-info">
          <h2>KONTAK</h2>
          <h3>Mari Bekerja Bersama</h3>
          <p>Siap untuk memulai proyek baru? Hubungi saya dan mari diskusikan ide-ide Anda.</p>
          <div class="contact-item">
            <div class="contact-icon"><i class="fas fa-envelope"></i></div>
            <div>
              <strong>Email</strong><br>
              ramamanansyah071@gmail.com
            </div>
          </div>
          <div class="contact-item">
            <div class="contact-icon"><i class="fas fa-phone"></i></div>
            <div>
              <strong>Telepon</strong><br>
              +62 812-3456-7890
            </div>
          </div>
          <div class="contact-item">
            <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div>
              <strong>Lokasi</strong><br>
              Jakarta, Indonesia
            </div>
          </div>
          <a href="#" class="btn btn-primary">Chat Sekarang</a>
        </div>
        <div class="contact-form">
          <form>
            <div class="form-group">
              <input type="text" placeholder="Nama Lengkap" class="form-control" required />
            </div>
            <div class="form-group">
              <input type="email" placeholder="Email" class="form-control" required />
            </div>
            <div class="form-group">
              <input type="text" placeholder="Subjek" class="form-control" required />
            </div>
            <div class="form-group">
              <textarea placeholder="Pesan Anda" class="form-control" required></textarea>
            </div>
            <button type="submit" class="submit-btn">Kirim Pesan</button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="container">
      <p class="footer-text">© 2023 rama manansyah. All rights reserved.</p>
    </div>
  </footer>

  <script src="script.js?v=<?=time()?>"></script>
</body>
</html>