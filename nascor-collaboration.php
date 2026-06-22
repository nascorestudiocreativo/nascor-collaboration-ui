<?php
/**
 * Plugin Name: Nascor Collaboration UI
 * Description: Interfaz interactiva de 4 columnas (Escritorio) y 2x2 (Móviles). Panel de administración completo para editar enlaces, medios y diseño.
 * Version: 2.0.0
 * Author: Nascor.ar
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Salir si se accede directamente
}

if ( ! class_exists( 'Nascor_Collaboration_Plugin' ) ) {

    class Nascor_Collaboration_Plugin {

        public function __construct() {
            // Registrar shortcode
            add_shortcode( 'nascor_collaboration', [ $this, 'render_shortcode' ] );
            
            // Panel de administrador
            add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
            add_action( 'admin_init', [ $this, 'register_settings' ] );
        }

        /**
         * ==========================================
         * 1. PANEL DE ADMINISTRADOR
         * ==========================================
         */
        public function add_admin_menu() {
            add_menu_page(
                'Nascor UI',
                'Nascor UI',
                'manage_options',
                'nascor-collaboration',
                [ $this, 'admin_page_html' ],
                'dashicons-layout',
                22
            );
        }

        public function register_settings() {
            $settings = [
                'ncu_bg_top', 'ncu_bg_bottom', 'ncu_logo_url',
                'ncu_link_linkedin', 'ncu_link_youtube', 'ncu_link_tiktok', 'ncu_link_pinterest',
                'ncu_link_twitter', 'ncu_link_bluesky', 'ncu_link_ig', 'ncu_link_fb',
                'ncu_panel1_img', 'ncu_panel2_vid', 'ncu_panel3_vid',
                'ncu_gal_img1', 'ncu_gal_img2', 'ncu_gal_img3', 'ncu_gal_img4'
            ];
            foreach ($settings as $setting) {
                register_setting( 'nascor_ui_settings', $setting );
            }
        }

        public function admin_page_html() {
            if ( ! current_user_can( 'manage_options' ) ) return;
            
            // Valores por defecto
            $defaults = [
                'ncu_bg_top' => '#162454',
                'ncu_bg_bottom' => '#0d1236',
                'ncu_logo_url' => 'https://nascor.ar/wp-content/uploads/2025/09/nascor-vertical-1-e1757731281748-1.avif',
                'ncu_link_linkedin' => 'https://www.linkedin.com/in/nascor-estudio-creativo/',
                'ncu_link_youtube' => 'https://www.youtube.com/@nascor_estudio_creativo',
                'ncu_link_tiktok' => 'https://www.tiktok.com/@nascor_ar',
                'ncu_link_pinterest' => 'https://ar.pinterest.com/nascor_ar/',
                'ncu_link_twitter' => 'https://x.com/Nascor_Ar',
                'ncu_link_bluesky' => 'https://bsky.app/profile/nascor.bsky.social',
                'ncu_link_ig' => 'https://www.instagram.com/nascor.ar/',
                'ncu_link_fb' => 'https://www.facebook.com/people/Nascor-Estudio-Creativo/61588687204618/',
                'ncu_panel1_img' => 'https://nascor.ar/wp-content/uploads/2026/04/Identidad-Visual-Profesional.avif',
                'ncu_panel2_vid' => 'https://nascor.ar/wp-content/uploads/2026/03/video-nascor-1.mp4',
                'ncu_panel3_vid' => 'https://nascor.ar/wp-content/uploads/2026/03/VIDEO-NASCOR-2.mp4',
                'ncu_gal_img1' => 'https://nascor.ar/wp-content/uploads/2026/04/ariana-huggins-ilustraciones-3.avif',
                'ncu_gal_img2' => 'https://nascor.ar/wp-content/uploads/2026/04/ariana-huggins-ilustraciones-2.avif',
                'ncu_gal_img3' => 'https://nascor.ar/wp-content/uploads/2026/04/ariana-huggins-ilustraciones-4.avif',
                'ncu_gal_img4' => 'https://nascor.ar/wp-content/uploads/2026/04/ariana-huggins-ilustraciones.avif'
            ];

            // Obtenemos los valores guardados o los reemplazamos por los defaults
            $options = [];
            foreach ($defaults as $key => $default_val) {
                $options[$key] = get_option($key, $default_val);
            }
            ?>
            <div class="wrap">
                <h1>Configuración de Nascor UI</h1>
                <div style="background: #fff; padding: 15px 20px; border-left: 4px solid #162454; margin-bottom: 20px;">
                    <h3>📌 Instrucciones de Integración</h3>
                    <p>Copia y pega el siguiente shortcode en cualquier parte de tu sitio web:</p>
                    <p><code style="font-size: 16px; padding: 5px 10px; background: #f0f0f1;">[nascor_collaboration]</code></p>
                </div>

                <div style="display: flex; gap: 30px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 350px; background: #fff; padding: 20px; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
                        <form method="post" action="options.php">
                            <?php settings_fields( 'nascor_ui_settings' ); ?>
                            
                            <h3 style="background:#f0f0f1; padding:10px;">🎨 Diseño y Logo</h3>
                            <table class="form-table">
                                <tr>
                                    <th scope="row">Color Fondo (Arriba)</th>
                                    <td><input type="color" name="ncu_bg_top" value="<?php echo esc_attr( $options['ncu_bg_top'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row">Color Fondo (Abajo)</th>
                                    <td><input type="color" name="ncu_bg_bottom" value="<?php echo esc_attr( $options['ncu_bg_bottom'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row">URL del Logo Central</th>
                                    <td><input type="text" name="ncu_logo_url" value="<?php echo esc_url( $options['ncu_logo_url'] ); ?>" class="regular-text" /></td>
                                </tr>
                            </table>

                            <h3 style="background:#f0f0f1; padding:10px;">🔗 Enlaces Sociales</h3>
                            <table class="form-table">
                                <?php
                                $socials = [
                                    'ncu_link_linkedin' => 'LinkedIn', 'ncu_link_youtube' => 'YouTube', 'ncu_link_tiktok' => 'TikTok',
                                    'ncu_link_pinterest' => 'Pinterest', 'ncu_link_twitter' => 'Twitter/X', 'ncu_link_bluesky' => 'Bluesky',
                                    'ncu_link_ig' => 'Instagram', 'ncu_link_fb' => 'Facebook'
                                ];
                                foreach ($socials as $key => $label) {
                                    echo '<tr><th scope="row">URL '.$label.'</th><td><input type="url" name="'.$key.'" value="'.esc_url( $options[$key] ).'" class="regular-text" /></td></tr>';
                                }
                                ?>
                            </table>

                            <h3 style="background:#f0f0f1; padding:10px;">🖼️ Contenido de los Paneles</h3>
                            <table class="form-table">
                                <tr>
                                    <th scope="row">Panel 1 (Identidad - Img URL)</th>
                                    <td><input type="url" name="ncu_panel1_img" value="<?php echo esc_url( $options['ncu_panel1_img'] ); ?>" class="regular-text" /></td>
                                </tr>
                                <tr>
                                    <th scope="row">Panel 2 (Redes - Video MP4)</th>
                                    <td><input type="url" name="ncu_panel2_vid" value="<?php echo esc_url( $options['ncu_panel2_vid'] ); ?>" class="regular-text" /></td>
                                </tr>
                                <tr>
                                    <th scope="row">Panel 3 (Promocional - Video MP4)</th>
                                    <td><input type="url" name="ncu_panel3_vid" value="<?php echo esc_url( $options['ncu_panel3_vid'] ); ?>" class="regular-text" /></td>
                                </tr>
                                <tr>
                                    <th scope="row">Panel 4 (Arte - Carrusel Img 1)</th>
                                    <td><input type="url" name="ncu_gal_img1" value="<?php echo esc_url( $options['ncu_gal_img1'] ); ?>" class="regular-text" /></td>
                                </tr>
                                <tr>
                                    <th scope="row">Panel 4 (Arte - Carrusel Img 2)</th>
                                    <td><input type="url" name="ncu_gal_img2" value="<?php echo esc_url( $options['ncu_gal_img2'] ); ?>" class="regular-text" /></td>
                                </tr>
                                <tr>
                                    <th scope="row">Panel 4 (Arte - Carrusel Img 3)</th>
                                    <td><input type="url" name="ncu_gal_img3" value="<?php echo esc_url( $options['ncu_gal_img3'] ); ?>" class="regular-text" /></td>
                                </tr>
                                <tr>
                                    <th scope="row">Panel 4 (Arte - Carrusel Img 4)</th>
                                    <td><input type="url" name="ncu_gal_img4" value="<?php echo esc_url( $options['ncu_gal_img4'] ); ?>" class="regular-text" /></td>
                                </tr>
                            </table>

                            <?php submit_button('Guardar Todo y Actualizar Vista Previa'); ?>
                        </form>
                    </div>

                    <div style="flex: 2; min-width: 500px;">
                        <h3>👁️ Vista Previa en Vivo</h3>
                        <div style="padding: 20px; background: #e0e0e0; border-radius: 8px;">
                            <?php echo do_shortcode('[nascor_collaboration]'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        /**
         * ==========================================
         * 2. RENDERIZADO DEL SHORTCODE
         * ==========================================
         */
        public function render_shortcode() {
            // Obtenemos opciones (con fallbacks si están vacíos)
            $bg_top = get_option('ncu_bg_top', '#162454');
            $bg_bottom = get_option('ncu_bg_bottom', '#0d1236');
            $logo = get_option('ncu_logo_url', 'https://nascor.ar/wp-content/uploads/2025/09/nascor-vertical-1-e1757731281748-1.avif');
            
            $link_in = get_option('ncu_link_linkedin', '#');
            $link_yt = get_option('ncu_link_youtube', '#');
            $link_tk = get_option('ncu_link_tiktok', '#');
            $link_pi = get_option('ncu_link_pinterest', '#');
            $link_tw = get_option('ncu_link_twitter', '#');
            $link_bs = get_option('ncu_link_bluesky', '#');
            $link_ig = get_option('ncu_link_ig', '#');
            $link_fb = get_option('ncu_link_fb', '#');

            $p1_img = get_option('ncu_panel1_img', '');
            $p2_vid = get_option('ncu_panel2_vid', '');
            $p3_vid = get_option('ncu_panel3_vid', '');
            $gal_1 = get_option('ncu_gal_img1', '');
            $gal_2 = get_option('ncu_gal_img2', '');
            $gal_3 = get_option('ncu_gal_img3', '');
            $gal_4 = get_option('ncu_gal_img4', '');

            ob_start();
            $this->print_css($bg_top, $bg_bottom);
            ?>

            <div class="nascor-ui-wrapper" id="nascor-main-container">
                <header class="nascor-header">
                    <div class="nascor-brand">
                        <div class="nascor-logo-btn" id="nascor-main-logo">
                            <img src="<?php echo esc_url($logo); ?>" width="50" height="50" class="nascor-spin-img" alt="Nascor Logo" fetchpriority="high">
                        </div>
                        <a href="<?php echo esc_url($link_in); ?>" target="_blank" class="nascor-interactable nascor-header-text-btn" id="btn-linkedin-header">
                            <img src="https://nascor.ar/wp-content/uploads/2026/04/linkedin.avif" width="16" height="16" alt="LinkedIn" style="object-fit: contain;"> LinkedIn
                        </a>
                    </div>
                    <div class="nascor-header-controls">
                        <a href="<?php echo esc_url($link_yt); ?>" target="_blank" class="nascor-interactable nascor-header-text-btn" id="btn-youtube-header">
                            <span style="color: red; font-size: 18px;">▶</span> YouTube
                        </a>
                        <a href="<?php echo esc_url($link_tk); ?>" target="_blank" class="nascor-interactable nascor-icon-btn" id="btn-tiktok">
                            <img src="https://nascor.ar/wp-content/uploads/2026/04/tiktok.avif" width="40" height="40" alt="TikTok">
                        </a>
                    </div>
                </header>

                <div class="nascor-grid">
                    <div class="nascor-panel" style="padding: 0;">
                        <img src="<?php echo esc_url($p1_img); ?>" class="nascor-bg-media" alt="Identidad Visual" width="400" height="533" loading="lazy">
                        <div class="nascor-media-overlay"></div>
                        <div class="nascor-panel-top-label">Identidad Visual</div>
                    </div>

                    <div class="nascor-panel" style="padding: 0;">
                        <video class="nascor-bg-media" width="400" height="533" muted loop playsinline preload="none">
                            <source src="<?php echo esc_url($p2_vid); ?>" type="video/mp4">
                        </video>
                        <div class="nascor-media-overlay"></div>
                        <div class="nascor-panel-top-label">Redes Sociales</div>
                    </div>

                    <div class="nascor-panel" style="padding: 0;">
                        <video class="nascor-bg-media" width="400" height="533" muted loop playsinline preload="none">
                            <source src="<?php echo esc_url($p3_vid); ?>" type="video/mp4">
                        </video>
                        <div class="nascor-media-overlay"></div>
                        <div class="nascor-panel-top-label">Videos Promocionales</div>
                    </div>

                    <div style="position: relative;">
                        <div class="nascor-panel" style="width: 100%; padding: 0;">
                            <div class="nascor-slider-container nascor-gallery-container">
                                <?php if($gal_1): ?><img src="<?php echo esc_url($gal_1); ?>" class="nascor-slide active" alt="Arte 1" loading="lazy"><?php endif; ?>
                                <?php if($gal_2): ?><img src="<?php echo esc_url($gal_2); ?>" class="nascor-slide" alt="Arte 2" loading="lazy"><?php endif; ?>
                                <?php if($gal_3): ?><img src="<?php echo esc_url($gal_3); ?>" class="nascor-slide" alt="Arte 3" loading="lazy"><?php endif; ?>
                                <?php if($gal_4): ?><img src="<?php echo esc_url($gal_4); ?>" class="nascor-slide" alt="Arte 4" loading="lazy"><?php endif; ?>
                            </div>
                            <div class="nascor-media-overlay"></div>
                            <div class="nascor-panel-top-label">Arte</div>
                        </div>
                        <a href="<?php echo esc_url($link_pi); ?>" target="_blank" class="nascor-interactable nascor-floating-btn" id="btn-pinterest">
                            <img src="https://nascor.ar/wp-content/uploads/2026/04/pinterest.avif" width="20" height="20" alt="Pinterest" style="object-fit: contain;" loading="lazy"> Pinterest
                        </a>
                    </div>
                </div>

                <footer class="nascor-footer">
                    <div class="nascor-social-group">
                        <a href="<?php echo esc_url($link_tw); ?>" target="_blank" class="nascor-interactable nascor-icon-btn">
                            <img src="https://nascor.ar/wp-content/uploads/2026/03/twitter.avif" width="40" height="40" alt="Twitter" loading="lazy">
                        </a>
                        <a href="<?php echo esc_url($link_bs); ?>" target="_blank" class="nascor-interactable nascor-icon-btn">
                            <img src="https://nascor.ar/wp-content/uploads/2026/04/bluesky.avif" width="40" height="40" alt="Bluesky" loading="lazy">
                        </a>
                    </div>
                    <a href="<?php echo esc_url($link_ig); ?>" target="_blank" class="nascor-interactable nascor-icon-btn">
                        <img src="https://nascor.ar/wp-content/uploads/2026/04/instagram.avif" width="40" height="40" alt="Instagram" loading="lazy">
                    </a>
                    <a href="<?php echo esc_url($link_fb); ?>" target="_blank" class="nascor-interactable nascor-icon-btn">
                        <img src="https://nascor.ar/wp-content/uploads/2026/04/Facebook.avif" width="40" height="40" alt="Facebook" loading="lazy">
                    </a>
                </footer>
            </div>

            <?php
            $this->print_js();
            return ob_get_clean();
        }

        /**
         * ==========================================
         * 3. CSS DINÁMICO
         * ==========================================
         */
        private function print_css($bg_top, $bg_bottom) {
            ?>
            <style>
                .nascor-ui-wrapper {
                    background: linear-gradient(180deg, <?php echo $bg_top; ?> 0%, <?php echo $bg_bottom; ?> 100%);
                    border-radius: 24px; padding: 20px; color: #ffffff;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    max-width: 1200px; margin: 0 auto; box-shadow: 0 10px 30px rgba(0,0,0,0.5);
                    position: relative; border: 1px solid rgba(255,255,255,0.1); box-sizing: border-box;
                }
                .nascor-interactable {
                    background: none; border: none; color: inherit; cursor: pointer;
                    padding: 0; font: inherit; transition: transform 0.2s, opacity 0.2s; text-decoration: none;
                }
                .nascor-interactable:hover { transform: scale(1.05); opacity: 0.9; }

                .nascor-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
                .nascor-brand { display: flex; align-items: center; gap: 15px; text-align: left; }
                
                @keyframes nascor-spin { 100% { transform: rotate(360deg); } }
                .nascor-logo-btn {
                    width: 50px; height: 50px; background: rgba(255,255,255,0.1); border-radius: 50%;
                    overflow: hidden; display: flex; align-items: center; justify-content: center;
                }
                .nascor-spin-img { width: 100%; height: auto; object-fit: cover; animation: nascor-spin 8s linear infinite; }

                .nascor-header-text-btn {
                    padding: 5px 15px; border-radius: 30px; font-weight: bold;
                    display: flex; align-items: center; gap: 5px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); font-size: 14px;
                }
                #btn-youtube-header { background: #ffffff; color: #000; }
                #btn-linkedin-header { background: #0A66C2; color: #ffffff; }

                .nascor-header-controls { display: flex; align-items: center; gap: 10px; }
                .nascor-icon-btn {
                    width: 40px; height: 40px; background: rgba(0,0,0,0.4); border-radius: 50%;
                    display: flex; align-items: center; justify-content: center; overflow: hidden;
                }
                .nascor-icon-btn img { width: 100%; height: auto; object-fit: cover; }

                .nascor-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; position: relative; }
                .nascor-panel {
                    background: #1e244d; border-radius: 16px; aspect-ratio: 3/4; position: relative;
                    overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end;
                    align-items: center; padding: 15px; border: 1px solid rgba(255,255,255,0.05);
                }
                
                .nascor-bg-media, .nascor-slider-container { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; }
                .nascor-bg-media, .nascor-slide { object-fit: cover; }
                .nascor-slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; transition: opacity 1s ease-in-out; z-index: 0; }
                .nascor-slide.active { opacity: 1; z-index: 1; }
                
                .nascor-media-overlay {
                    position: absolute; top: 0; left: 0; width: 100%; height: 100%;
                    background: linear-gradient(0deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 50%); z-index: 1; pointer-events: none;
                }
                .nascor-panel-top-label {
                    position: absolute; top: 10px; left: 10px; background: rgba(0,0,0,0.5);
                    padding: 4px 8px; border-radius: 10px; font-size: 10px; z-index: 2;
                }

                .nascor-floating-btn {
                    position: absolute; padding: 10px 20px; border-radius: 30px; font-weight: bold;
                    display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); z-index: 10;
                }
                #btn-pinterest { right: -20px; top: 0.3%; background: #E60023; color: #ffffff; }

                .nascor-footer {
                    margin-top: 20px; background: rgba(0,0,0,0.3); border-radius: 0 0 24px 24px;
                    padding: 15px; display: flex; justify-content: space-between; align-items: center;
                    border-top: 1px solid rgba(255,255,255,0.1);
                }
                .nascor-social-group { display: flex; align-items: center; gap: 10px; }

                @media (max-width: 900px) {
                    .nascor-ui-wrapper { max-width: 600px; }
                    .nascor-grid { grid-template-columns: 1fr 1fr; }
                }

                @media (max-width: 550px) {
                    .nascor-header { flex-direction: column; gap: 15px; }
                    .nascor-brand, .nascor-header-controls { width: 100%; justify-content: center; flex-wrap: wrap; gap: 8px; }
                    .nascor-header .nascor-logo-btn { width: 40px; height: 40px; }
                    .nascor-header .nascor-header-text-btn { font-size: 12px; padding: 6px 12px; }
                    .nascor-header .nascor-icon-btn { width: 35px; height: 35px; }
                }
            </style>
            <?php
        }

        /**
         * ==========================================
         * 4. JAVASCRIPT: OBSERVER & SLIDER
         * ==========================================
         */
        private function print_js() {
            ?>
            <script>
                // IIFE para evitar conflictos en el panel de admin si hay varios shortcodes renderizados
                (function() {
                    document.addEventListener('DOMContentLoaded', function() {
                        const containers = document.querySelectorAll('.nascor-ui-wrapper');
                        
                        containers.forEach(container => {
                            // Control de Videos
                            const videos = container.querySelectorAll('video.nascor-bg-media');
                            if ('IntersectionObserver' in window) {
                                const videoObserver = new IntersectionObserver((entries) => {
                                    entries.forEach(entry => {
                                        if (entry.isIntersecting) {
                                            entry.target.play().catch(e => console.log('Autoplay bloqueado', e));
                                        } else {
                                            entry.target.pause();
                                        }
                                    });
                                }, { threshold: 0.1 });

                                videos.forEach(video => videoObserver.observe(video));
                            } else {
                                videos.forEach(video => video.play());
                            }

                            // Control de Carrusel
                            const galleryContainers = container.querySelectorAll('.nascor-gallery-container');
                            galleryContainers.forEach(gallery => {
                                const slides = gallery.querySelectorAll('.nascor-slide');
                                if (slides.length > 1) {
                                    let currentSlide = 0;
                                    let slideInterval = null;

                                    const startCarousel = () => {
                                        if (!slideInterval) {
                                            slideInterval = setInterval(() => {
                                                slides[currentSlide].classList.remove('active');
                                                currentSlide = (currentSlide + 1) % slides.length;
                                                slides[currentSlide].classList.add('active');
                                            }, 3500);
                                        }
                                    };

                                    const stopCarousel = () => {
                                        if (slideInterval) {
                                            clearInterval(slideInterval);
                                            slideInterval = null;
                                        }
                                    };

                                    if ('IntersectionObserver' in window) {
                                        const galleryObserver = new IntersectionObserver((entries) => {
                                            entries.forEach(entry => {
                                                entry.isIntersecting ? startCarousel() : stopCarousel();
                                            });
                                        }, { threshold: 0.1 });
                                        galleryObserver.observe(gallery);
                                    } else {
                                        startCarousel();
                                    }
                                }
                            });
                        });
                    });
                })();
            </script>
            <?php
        }
    }

    new Nascor_Collaboration_Plugin();
}