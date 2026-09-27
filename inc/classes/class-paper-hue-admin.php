<?php
/**
 * Paper Hue admin dashboard.
 *
 * @package Paper_Hue
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds the Paper Hue control center beneath Appearance.
 */
final class Paper_Hue_Admin {
	/**
	 * Config service.
	 *
	 * @var Paper_Hue_Config
	 */
	private $config;

	/**
	 * Constructor.
	 *
	 * @param Paper_Hue_Config $config Config service.
	 */
	public function __construct( Paper_Hue_Config $config ) {
		$this->config = $config;
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'admin_menu', array( $this, 'register_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Register Appearance > Paper Hue.
	 *
	 * @return void
	 */
	public function register_page() {
		add_theme_page(
			__( 'Paper Hue', 'paper-hue' ),
			__( 'Paper Hue', 'paper-hue' ),
			'edit_theme_options',
			'paper-hue',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Enqueue assets only on the Paper Hue dashboard.
	 *
	 * @param string $hook_suffix Current admin hook suffix.
	 * @return void
	 */
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

	/**
	 * Build a Customizer deep link.
	 *
	 * @param string $section Section or panel identifier.
	 * @return string
	 */
	private function customize_url( $section ) {
		$url = add_query_arg(
			array(
				'autofocus[section]' => sanitize_key( $section ),
				'url'                => rawurlencode( home_url( '/' ) ),
			),
			admin_url( 'customize.php' )
		);

		return esc_url( $url );
	}

	/**
	 * Render dashboard status card.
	 *
	 * @param array<string,mixed> $status Status payload.
	 * @return void
	 */
	private function render_status_card( $status ) {
		$healthy = ! empty( $status['healthy'] );
		?>
		<article class="paper-hue-admin-card">
			<div class="paper-hue-admin-card__header">
				<h2><?php echo esc_html( $status['label'] ); ?></h2>
				<span class="paper-hue-admin-status <?php echo $healthy ? 'is-ready' : 'needs-attention'; ?>">
					<?php echo $healthy ? esc_html__( 'Ready', 'paper-hue' ) : esc_html__( 'Needs attention', 'paper-hue' ); ?>
				</span>
			</div>
			<p><?php echo esc_html( $status['summary'] ); ?></p>
			<a class="button button-secondary" href="<?php echo $this->customize_url( $status['customizer'] ); ?>">
				<?php esc_html_e( 'Customize', 'paper-hue' ); ?>
			</a>
		</article>
		<?php
	}

	/**
	 * Render the dashboard page.
	 *
	 * @return void
	 */
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
		?>
		<div class="wrap paper-hue-admin">
			<header class="paper-hue-admin-hero">
				<div>
					<p class="paper-hue-admin-eyebrow"><?php esc_html_e( 'Paper Hue', 'paper-hue' ); ?></p>
					<h1><?php esc_html_e( 'Theme Control Center', 'paper-hue' ); ?></h1>
					<p class="paper-hue-admin-lede">
						<?php esc_html_e( 'A concise view of the pieces that shape your Paper Hue homepage. Presentation settings remain in the WordPress Customizer so there is one source of truth.', 'paper-hue' ); ?>
					</p>
				</div>
				<div class="paper-hue-admin-score" aria-label="<?php echo esc_attr( sprintf( __( '%1$d of %2$d theme areas ready', 'paper-hue' ), $ready_count, $total_count ) ); ?>">
					<strong><?php echo esc_html( $ready_count ); ?>/<?php echo esc_html( $total_count ); ?></strong>
					<span><?php esc_html_e( 'areas ready', 'paper-hue' ); ?></span>
				</div>
			</header>

			<div class="paper-hue-admin-actions">
				<a class="button button-primary button-hero" href="<?php echo esc_url( admin_url( 'customize.php?url=' . rawurlencode( home_url( '/' ) ) ) ); ?>">
					<?php esc_html_e( 'Customize Homepage', 'paper-hue' ); ?>
				</a>
				<a class="button button-secondary button-hero" href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>">
					<?php esc_html_e( 'Manage Posts', 'paper-hue' ); ?>
				</a>
				<a class="button button-secondary button-hero" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'View Site', 'paper-hue' ); ?>
				</a>
			</div>

			<section class="paper-hue-admin-section" aria-labelledby="paper-hue-status-heading">
				<div class="paper-hue-admin-section__heading">
					<h2 id="paper-hue-status-heading"><?php esc_html_e( 'Homepage status', 'paper-hue' ); ?></h2>
					<p><?php esc_html_e( 'These checks do not change your site. They surface the configuration already used by Paper Hue.', 'paper-hue' ); ?></p>
				</div>

				<div class="paper-hue-admin-grid">
					<?php
					foreach ( $statuses as $status ) {
						$this->render_status_card( $status );
					}
					?>
				</div>
			</section>

			<section class="paper-hue-admin-section paper-hue-admin-two-column" aria-labelledby="paper-hue-content-heading">
				<div class="paper-hue-admin-panel">
					<h2 id="paper-hue-content-heading"><?php esc_html_e( 'Featured Story', 'paper-hue' ); ?></h2>
					<?php if ( $this->config->featured_story_enabled() && $featured_story ) : ?>
						<h3><?php echo esc_html( get_the_title( $featured_story ) ); ?></h3>
						<p>
							<?php
							echo esc_html(
								sprintf(
									/* translators: %s: publication date. */
									__( 'Published %s', 'paper-hue' ),
									get_the_date( '', $featured_story )
								)
							);
							?>
						</p>
						<div class="paper-hue-admin-inline-actions">
							<a class="button" href="<?php echo esc_url( get_edit_post_link( $featured_story->ID, 'raw' ) ); ?>"><?php esc_html_e( 'Edit Story', 'paper-hue' ); ?></a>
							<a class="button" href="<?php echo $this->customize_url( 'feat_post' ); ?>"><?php esc_html_e( 'Configure Feature', 'paper-hue' ); ?></a>
						</div>
					<?php elseif ( $this->config->featured_story_enabled() ) : ?>
						<p><?php esc_html_e( 'Featured Story is enabled, but no published sticky post is available yet.', 'paper-hue' ); ?></p>
						<a class="button" href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>"><?php esc_html_e( 'Choose a Sticky Post', 'paper-hue' ); ?></a>
					<?php else : ?>
						<p><?php esc_html_e( 'Featured Story is currently disabled.', 'paper-hue' ); ?></p>
						<a class="button" href="<?php echo $this->customize_url( 'feat_post' ); ?>"><?php esc_html_e( 'Enable Featured Story', 'paper-hue' ); ?></a>
					<?php endif; ?>
				</div>

				<div class="paper-hue-admin-panel">
					<h2><?php esc_html_e( 'Paper Hue philosophy', 'paper-hue' ); ?></h2>
					<p><?php esc_html_e( 'Keep the familiar paper-material aesthetic. Modernize the engine beneath it: compatibility, accessibility, performance, semantics, and maintainability.', 'paper-hue' ); ?></p>
					<ul class="paper-hue-admin-checklist">
						<li><?php esc_html_e( 'Customizer remains the presentation source of truth.', 'paper-hue' ); ?></li>
						<li><?php esc_html_e( 'Existing theme mods and user content remain compatible.', 'paper-hue' ); ?></li>
						<li><?php esc_html_e( 'Admin screens diagnose and navigate instead of duplicating settings.', 'paper-hue' ); ?></li>
					</ul>
				</div>
			</section>
		</div>
		<?php
	}
}
