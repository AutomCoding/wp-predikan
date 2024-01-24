<?php
/**
 * Text Domain: predikan
 */
$episodes = $this->episodes_data( 30 );
echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?>';
?>

<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">
	<channel>
		<title><?php echo esc_xml( get_option( 'predikan_title' ) ); ?></title>
		<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
		<link><?php bloginfo( 'url' ); ?></link>
		<description><?php echo esc_xml( get_option( 'predikan_description' ) ); ?></description>
		<itunes:author><?php echo esc_xml( get_option( 'predikan_author' ) ); ?></itunes:author>
		<itunes:category text="Religion &amp; Spirituality">
			<itunes:category text="Christianity"/>
		</itunes:category>
		<itunes:image href="<?php echo plugin_dir_url( __FILE__ ) . 'images/channel-logo.png'; ?>"/>
		<itunes:type>episodic</itunes:type>
		<itunes:explicit>clean</itunes:explicit>
		<itunes:owner>
			<itunes:name><?php echo esc_xml( get_option( 'predikan_owner_name' ) ); ?></itunes:name>
			<itunes:email><?php echo esc_xml( get_option( 'predikan_owner_email' ) ); ?></itunes:email>
		</itunes:owner>
		<lastBuildDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_lastpostmodified( 'GMT' ), false ); ?></lastBuildDate>
		<language><?php echo substr( get_locale(), 0, 2 ); ?></language>
		<sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
		<sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
		<?php foreach( $episodes as $ep ): ?>

		<item>
			<title><?php echo esc_xml( $ep[ 'title' ] ); ?></title>
			<link><?php echo $ep[ 'permalink' ]; ?></link>
			<description><![CDATA[<?php echo $ep[ 'title' ]; ?>]]></description>
			<pubDate><?php echo date( 'r', $ep[ 'unix_time' ] ); ?></pubDate>
			<content:encoded><![CDATA[<?php echo $ep[ 'title' ] . '<br/>' . $ep[ 'speakers_string' ] . ' ' . $ep[ 'date' ]; ?>]]></content:encoded>
			<enclosure url="<?php echo esc_attr( $ep[ 'file' ] ); ?>" length="<?php echo filesize( $ep[ 'file' ] ); ?>" type="audio/mpeg"/>
		</item>
		<?php endforeach; ?>

	</channel>
</rss>
