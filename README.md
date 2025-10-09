# force_elastic — Roundcube plugin

[![Packagist Downloads](https://img.shields.io/packagist/dt/texxasrulez/force_elastic?style=plastic&logo=packagist&logoColor=white&label=Downloads&labelColor=blue&color=gold)](https://packagist.org/packages/texxasrulez/force_elastic)
[![Packagist Version](https://img.shields.io/packagist/v/texxasrulez/force_elastic?style=plastic&logo=packagist&logoColor=white&label=Version&labelColor=blue&color=limegreen)](https://packagist.org/packages/texxasrulez/force_elastic)
[![Github License](https://img.shields.io/github/license/texxasrulez/force_elastic?style=plastic&logo=github&label=License&labelColor=blue&color=coral)](https://github.com/texxasrulez/force_elastic/LICENSE)
[![GitHub Stars](https://img.shields.io/github/stars/texxasrulez/force_elastic?style=plastic&logo=github&label=Stars&labelColor=blue&color=deepskyblue)](https://github.com/texxasrulez/force_elastic/stargazers)
[![GitHub Issues](https://img.shields.io/github/issues/texxasrulez/force_elastic?style=plastic&logo=github&label=Issues&labelColor=blue&color=aqua)](https://github.com/texxasrulez/force_elastic/issues)
[![GitHub Contributors](https://img.shields.io/github/contributors/texxasrulez/force_elastic?style=plastic&logo=github&logoColor=white&label=Contributors&labelColor=blue&color=orchid)](https://github.com/texxasrulez/force_elastic/graphs/contributors)
[![GitHub Forks](https://img.shields.io/github/forks/texxasrulez/force_elastic?style=plastic&logo=github&logoColor=white&label=Forks&labelColor=blue&color=darkorange)](https://github.com/texxasrulez/force_elastic/forks)
[![Donate Paypal](https://img.shields.io/badge/Paypal-Money_Please!-blue.svg?style=plastic&labelColor=blue&color=forestgreen&logo=paypal)](https://www.paypal.me/texxasrulez)

Forces the **Elastic** skin for requests coming from mobile devices — without changing the user's saved skin preference or your system default. This keeps small screens functional even when users have selected legacy/non-responsive skins.

- **Repo:** https://github.com/texxasrulez/force_elastic
- **Type:** `roundcube-plugin`
- **Requires:** Roundcube **1.4+** (Elastic introduced in 1.4), PHP **7.3+**

## What it does

- Detects mobile clients early in request flow (`startup` and `authenticate` hooks).
- Sets the runtime skin to `elastic` and, when present, updates the Output object's skin.
- Uses Roundcube's browser detector if available; otherwise falls back to a tight UA regex.
- Never persists preferences. Desktop users keep their chosen skin.

---

## Install via Composer (recommended)

This path installs straight from Packagist/Git and lets Roundcube's plugin-installer wire it up.

1) From your Roundcube root directory:
```bash
composer require texxasrulez/force_elastic
```

2) Enable the plugin in `config/config.inc.php`:
```php
// Append to your existing list, do not duplicate other plugins
$config['plugins'] = array_merge($config['plugins'] ?? [], ['force_elastic']);
// or set explicitly:
// $config['plugins'] = ['force_elastic', /* other plugins... */];
```

3) Clear Roundcube caches (good hygiene after plugin changes):
```bash
bin/cleandb.sh || true
bin/update.sh --skip-deps || true
```

### Installing directly from Git (optional)

If you prefer to track the GitHub repo without Packagist:

```bash
cd /path/to/roundcube
composer config repositories.force_elastic git https://github.com/texxasrulez/force_elastic.git
composer require texxasrulez/force_elastic:dev-main
```

---

## Manual install (no Composer)

1) Copy the `force_elastic` plugin folder into Roundcube:
```
/path/to/roundcube/plugins/force_elastic
```

2) Enable it in `config/config.inc.php` as shown above.

---

## Configuration

An optional config file is provided. Copy `plugins/force_elastic/config.inc.php.dist` to `plugins/force_elastic/config.inc.php` and adjust as needed.

```php
// Skin to force on mobile devices (keep 'elastic' unless you know what you're doing)
$config['force_elastic.skin'] = 'elastic';

// Optionally disable the fallback UA sniff; rely solely on Roundcube's browser detector
$config['force_elastic.use_ua_sniff'] = true;
```

No configuration is required for default behavior.

---

## How detection works

1. Prefer Roundcube's built-in browser detector (if available on your version/path).
2. Fall back to a conservative `User-Agent` regex for common phones/tablets.
3. Apply skin change at runtime only; no DB writes and no preference mutations.

---

## Uninstall

- Remove `'force_elastic'` from `$config['plugins']` in `config/config.inc.php`.
- If installed via Composer: `composer remove texxasrulez/force_elastic`.
- Delete `plugins/force_elastic/` if manually installed.

---

## Troubleshooting

- **Elastic isn’t applied on mobile:** Check proxies/CDNs that might rewrite `User-Agent`. If so, rely on Roundcube's detector by disabling UA sniff in the plugin config.
- **Conflicts with other skin/branding plugins:** Ensure `force_elastic` is loaded after any plugin that explicitly sets a skin for all requests.
- **Caching:** Clear browser cache and Roundcube caches after first install.

