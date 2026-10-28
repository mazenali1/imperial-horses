
<?php
get_header();

$lang = apply_filters( 'wpml_current_language', null );
$ar   = ( $lang === 'ar' ) || is_rtl();

$banner_id = get_theme_mod(
    $ar ? 'ih_404_banner_ar' : 'ih_404_banner_en'
);

$bg = $banner_id
    ? wp_get_attachment_image_url( (int) $banner_id, 'full' )
    : '';

?>

<section class="hero" <?php
    if ( $bg ) {
        echo 'style="--hero-bg:url(' . esc_url( $bg ) . ')"';
    }
?>>
    <div class="container hero__inner">

        <p class="hero__kicker">404</p>

        <h1 class="hero__title">
            <?php echo esc_html( $ar ? 'الصفحة غير موجودة' : 'Page Not Found' ); ?>
        </h1>

        <p class="hero__text">
            <?php
            echo esc_html(
                $ar
                    ? 'الصفحة التي تبحث عنها غير موجودة أو تم نقلها.'
                    : "The page you are looking for doesn't exist or has been moved."
            );
            ?>
        </p>

        <a class="btn btn--outline" href="<?php echo esc_url( home_url( '/' ) ); ?>">
            <span class="btn__label">
                <?php echo esc_html( $ar ? 'العودة للرئيسية' : 'Back to Home' ); ?>
            </span>

            <span class="btn__icon">
                <svg class="icon" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M13 6l6 6-6 6"/>
                </svg>
            </span>
        </a>

    </div>
</section>

<?php get_footer(); ?>
