<?php
/**
 * Paper Hue Theme Control Center.
 *
 * @package Paper_Hue
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds the Paper Hue Theme Control Center beneath Appearance.
 *
 * TCC is intentionally read-only for theme presentation settings. The
 * Customizer remains the single write authority for Paper Hue theme mods.
 */
final class Paper_Hue_Admin {
	/** @var Paper_Hue_Config */
	private $config;

	public function __construct( Paper_Hue_Config $config ) {
		$this->config = $config;
	}

	public function register() {
		add_action( 'admin_menu', array( $this, 'register_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	public function register_page() {
		add_theme_page(
			__( 'Theme Control Center', 'paper-hue' ),
			__( 'Paper Hue', 'paper-hue' ),
			'edit_theme_options',
			'paper-hue',
			array( $this, 'render_page' )
		);
	}

	public function enqueue_assets( $hook_suffix ) {
		if ( 'appearance_page_paper-hue' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style(
			'paper-hue-admin',
			get_template_directory_uri() . '/client-side/css/paper-hue-admin.css',
			array(),
			paper_hue_asset_version( 'client-side/css/paper-hue-admin.css' )
		);
	}

	private function customize_url( $target ) {
		$target_type = in_array( $target, array( 'nav_menus', 'widgets' ), true ) ? 'panel' : 'section';
		return add_query_arg(
			array(
				'autofocus[' . $target_type . ']' => sanitize_key( $target ),
				'url'                              => rawurlencode( home_url( '/' ) ),
			),
			admin_url( 'customize.php' )
		);
	}

	private function render_status_card( $status ) {
		$healthy = ! empty( $status['healthy'] );
		?>
		<article class="paper-hue-admin-card <?php echo esc_attr( $healthy ? 'is-ready' : 'needs-attention' ); ?>">
			<div class="paper-hue-admin-card__header">
				<div>
					<span class="paper-hue-admin-card__kicker"><?php esc_html_e( 'Customizer-owned', 'paper-hue' ); ?></span>
					<h3><?php echo esc_html( $status['label'] ); ?></h3>
				</div>
				<span class="paper-hue-admin-status <?php echo esc_attr( $healthy ? 'is-ready' : 'needs-attention' ); ?>">
					<?php echo esc_html( $healthy ? __( 'Ready', 'paper-hue' ) : __( 'Review', 'paper-hue' ) ); ?>
				</span>
			</div>
			<p><?php echo esc_html( $status['summary'] ); ?></p>
			<a class="button button-secondary" href="<?php echo esc_url( $this->customize_url( $status['customizer'] ) ); ?>">
				<?php esc_html_e( 'Open controls', 'paper-hue' ); ?>
			</a>
		</article>
		<?php
	}

	private function render_metric( $label, $value, $detail = '' ) {
		?>
		<div class="paper-hue-admin-metric">
			<span><?php echo esc_html( $label ); ?></span>
			<strong><?php echo esc_html( $value ); ?></strong>
			<?php if ( $detail ) : ?>
				<small><?php echo esc_html( $detail ); ?></small>
			<?php endif; ?>
		</div>
		<?php
	}

	public function render_page() {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to manage Paper Hue.', 'paper-hue' ) );
		}

		$statuses       = $this->config->dashboard_status();
		$featured_story = $this->config->featured_story();
		$ready_count    = count(
			array_filter(
				$statuses,
				static function ( $status ) {
					return ! empty( $status['healthy'] );
				}
			)
		);
		$total_count    = count( $statuses );
		$widget_count   = count( $this->config->active_widget_areas() );
		$slider_status  = $this->config->slider_enabled() ? __( 'On', 'paper-hue' ) : __( 'Off', 'paper-hue' );
		$feature_status = $this->config->featured_story_enabled() ? __( 'On', 'paper-hue' ) : __( 'Off', 'paper-hue' );
		$layout_labels  = array(
			'classic' => __( 'Classic', 'paper-hue' ),
			'compact' => __( 'Compact', 'paper-hue' ),
			'list'    => __( 'List', 'paper-hue' ),
		);
		$recent_layout = $this->config->recent_layout();
		?>
		<div class="wrap paper-hue-admin">
			<header class="paper-hue-admin-hero">
				<div class="paper-hue-admin-hero__content">
					<div class="paper-hue-admin-brandline">
						<span class="paper-hue-admin-mark" aria-hidden="true">PH</span>
						<div>
							<p class="paper-hue-admin-eyebrow"><?php esc_html_e( 'Paper Hue', 'paper-hue' ); ?></p>
							<h1><?php esc_html_e( 'Theme Control Center', 'paper-hue' ); ?></h1>
						</div>
					</div>
					<p class="paper-hue-admin-lede"><?php esc_html_e( 'Your command deck for Paper Hue. Review the live composition, spot configuration gaps, and jump directly to the exact Customizer controls that own each visual decision.', 'paper-hue' ); ?></p>
					<div class="paper-hue-admin-actions">
						<a class="button button-primary button-hero" href="<?php echo esc_url( admin_url( 'customize.php?url=' . rawurlencode( home_url( '/' ) ) ) ); ?>"><?php esc_html_e( 'Open Customizer', 'paper-hue' ); ?></a>
						<a class="button button-secondary button-hero" href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>"><?php esc_html_e( 'Manage Stories', 'paper-hue' ); ?></a>
						<a class="button button-secondary button-hero" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View Site', 'paper-hue' ); ?></a>
					</div>
				</div>
				<?php /* translators: 1: ready areas count, 2: total areas count. */ ?>
				<div class="paper-hue-admin-score" aria-label="<?php echo esc_attr( sprintf( __( '%1$d of %2$d theme areas ready', 'paper-hue' ), $ready_count, $total_count ) ); ?>">
					<span class="paper-hue-admin-score__label"><?php esc_html_e( 'Configuration health', 'paper-hue' ); ?></span>
					<strong><?php echo esc_html( $ready_count ); ?><em>/<?php echo esc_html( $total_count ); ?></em></strong>
					<span><?php esc_html_e( 'areas ready', 'paper-hue' ); ?></span>
					<?php /* translators: %s: Paper Hue theme version. */ ?>
					<small><?php echo esc_html( sprintf( __( 'Paper Hue %s', 'paper-hue' ), PAPER_HUE_VERSION ) ); ?></small>
				</div>
			</header>

			<section class="paper-hue-admin-metrics" aria-label="<?php esc_attr_e( 'Homepage composition summary', 'paper-hue' ); ?>">
				<?php $this->render_metric( __( 'Hero Slider', 'paper-hue' ), $slider_status, $this->config->slider_source_label() ); ?>
				<?php $this->render_metric( __( 'Featured Story', 'paper-hue' ), $feature_status, $featured_story ? get_the_title( $featured_story ) : __( 'No active story', 'paper-hue' ) ); ?>
				<?php /* translators: %d: number of recent articles per page. */ ?>
				<?php $this->render_metric( __( 'Recent Articles', 'paper-hue' ), $layout_labels[ $recent_layout ], sprintf( __( '%d per page', 'paper-hue' ), $this->config->recent_per_page() ) ); ?>
				<?php $this->render_metric( __( 'Widget Areas', 'paper-hue' ), (string) $widget_count, __( 'active areas', 'paper-hue' ) ); ?>
			</section>

			<section class="paper-hue-admin-section" aria-labelledby="paper-hue-control-map-heading">
				<div class="paper-hue-admin-section__heading">
					<div>
						<p class="paper-hue-admin-eyebrow"><?php esc_html_e( 'One source of truth', 'paper-hue' ); ?></p>
						<h2 id="paper-hue-control-map-heading"><?php esc_html_e( 'Customizer control map', 'paper-hue' ); ?></h2>
					</div>
					<p><?php esc_html_e( 'TCC never stores duplicate theme options. Every card opens the canonical WordPress Customizer control surface.', 'paper-hue' ); ?></p>
				</div>
				<div class="paper-hue-admin-grid">
					<?php foreach ( $statuses as $status ) { $this->render_status_card( $status ); } ?>
				</div>
			</section>

			<section class="paper-hue-admin-section paper-hue-admin-two-column" aria-labelledby="paper-hue-composition-heading">
				<div class="paper-hue-admin-panel paper-hue-admin-panel--feature">
					<p class="paper-hue-admin-eyebrow"><?php esc_html_e( 'Homepage composition', 'paper-hue' ); ?></p>
					<h2 id="paper-hue-composition-heading"><?php esc_html_e( 'Featured Story', 'paper-hue' ); ?></h2>
					<?php if ( $this->config->featured_story_enabled() && $featured_story ) : ?>
						<h3><?php echo esc_html( get_the_title( $featured_story ) ); ?></h3>
						<p><?php /* translators: %s: publication date. */ echo esc_html( sprintf( __( 'Published %s', 'paper-hue' ), get_the_date( '', $featured_story ) ) ); ?></p>
						<div class="paper-hue-admin-inline-actions">
							<a class="button" href="<?php echo esc_url( get_edit_post_link( $featured_story->ID, 'raw' ) ); ?>"><?php esc_html_e( 'Edit story', 'paper-hue' ); ?></a>
							<a class="button" href="<?php echo esc_url( $this->customize_url( 'feat_post' ) ); ?>"><?php esc_html_e( 'Customize feature', 'paper-hue' ); ?></a>
						</div>
					<?php elseif ( $this->config->featured_story_enabled() ) : ?>
						<p><?php esc_html_e( 'Featured Story is enabled but its configured source currently has no publishable story.', 'paper-hue' ); ?></p>
						<a class="button" href="<?php echo esc_url( $this->customize_url( 'feat_post' ) ); ?>"><?php esc_html_e( 'Review feature source', 'paper-hue' ); ?></a>
					<?php else : ?>
						<p><?php esc_html_e( 'Featured Story is currently disabled.', 'paper-hue' ); ?></p>
						<a class="button" href="<?php echo esc_url( $this->customize_url( 'feat_post' ) ); ?>"><?php esc_html_e( 'Open Featured Story controls', 'paper-hue' ); ?></a>
					<?php endif; ?>
				</div>

				<div class="paper-hue-admin-panel paper-hue-admin-panel--principles">
					<p class="paper-hue-admin-eyebrow"><?php esc_html_e( 'Control architecture', 'paper-hue' ); ?></p>
					<h2><?php esc_html_e( 'Premium without duplication', 'paper-hue' ); ?></h2>
					<ul class="paper-hue-admin-checklist">
						<li><strong><?php esc_html_e( 'TCC', 'paper-hue' ); ?></strong> <?php esc_html_e( 'shows health, composition, publishing shortcuts, and navigation.', 'paper-hue' ); ?></li>
						<li><strong><?php esc_html_e( 'Customizer', 'paper-hue' ); ?></strong> <?php esc_html_e( 'owns all Paper Hue presentation settings and live preview.', 'paper-hue' ); ?></li>
						<li><strong><?php esc_html_e( 'WordPress', 'paper-hue' ); ?></strong> <?php esc_html_e( 'continues to own Site Identity, Menus, Widgets, posts, and media.', 'paper-hue' ); ?></li>
					</ul>
					<p class="paper-hue-admin-note"><?php esc_html_e( 'No TCC-only appearance values. No hidden shadow configuration. Existing theme mods remain authoritative.', 'paper-hue' ); ?></p>
				</div>
			</section>
		</div>
		<?php
	}
}
