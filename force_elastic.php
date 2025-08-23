<?php
/**
 * Plugin: force_elastic
 * Purpose: Detect mobile devices and force the Elastic skin regardless of user/default skin.
 * Works on all tasks (login, mail, settings, etc.).
 *
 * Install:
 *   - Copy `plugins/force_elastic` into your Roundcube `plugins/` directory
 *   - Add 'force_elastic' to $config['plugins'] in config/config.inc.php
 *
 * This plugin avoids changing the user's saved preference; it only overrides at runtime.
 */
class force_elastic extends rcube_plugin
{
    public $task = '.*'; // apply to every task

    public function init()
    {
        // Load plugin config (optional overrides)
        $this->load_config();

        // Hook as early as possible
        $this->add_hook('startup', array($this, 'force_skin'));
        // Also hook authenticate to catch early login/ajax paths
        $this->add_hook('authenticate', array($this, 'force_skin'));
    }

    /**
     * Hook handler: enforce Elastic skin if a mobile device is detected.
     */
    public function force_skin($args)
    {
        $rc = rcube::get_instance();

        if ($this->is_mobile_request()) {
            // Set runtime skin to Elastic without persisting user prefs
            $rc->config->set('skin', 'elastic');

            // If output object exists and supports dynamic skin change, apply it too
            if (isset($rc->output) && is_object($rc->output) && method_exists($rc->output, 'set_skin')) {
                $rc->output->set_skin('elastic');
            }
        }

        return $args;
    }

    /**
     * Determine whether current request is from a mobile device.
     * Prefer Roundcube's browser detector when available, otherwise fall back to UA sniff.
     */
    private function is_mobile_request()
    {
        $rc = rcube::get_instance();

        // Prefer Roundcube browser detector if available
        if (isset($rc->output) && is_object($rc->output) 
            && isset($rc->output->browser) && is_object($rc->output->browser)) {

            $browser = $rc->output->browser;

            // rcube_browser may expose a boolean property or is_mobile() method depending on RC version
            if (property_exists($browser, 'mobile') && $browser->mobile) {
                return true;
            }

            if (method_exists($browser, 'is_mobile')) {
                return (bool) $browser->is_mobile();
            }
        }

        // Fallback: simple and reasonably robust UA sniff
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        if (!$ua) {
            return false;
        }

        $pattern = '/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB10|IEMobile|Opera Mini|Mobile|Silk|Kindle|Phone/i';
        return (bool) preg_match($pattern, $ua);
    }
}
?>
