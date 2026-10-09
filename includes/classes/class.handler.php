<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 *
 * @version 1.2.2
 * (Fixes PHP 8.x deprecations / notices)
 */

class Handler {

	private $dB;
	private $_disableWebEngineFooterVersion = false;
	private $_disableWebEngineFooterCredits = false;

	public function loadPage() {
		global $config,$lang,$custom,$tSettings;

		# object instances
		$handler = $this;

		# load language
		if(strtolower($config['language_default']) != 'en') {
			$this->_loadLanguagePhrases('en');
		}
		$this->_loadLanguagePhrases($config['language_default']);

		// --- FIX: evitar Undefined array key "language_display"
		$langSwitch  = !empty($config['language_switch_active']);
		$langDisplay = $_SESSION['language_display'] ?? null;
		if($langSwitch && check_value($langDisplay)) {
			if($langDisplay != $config['language_default']) {
				$this->_loadLanguagePhrases($langDisplay);
			}
		}
		// --- END FIX

		# access
		if(!defined('access')) throw new Exception('Access forbidden.');
		switch(access) {
			case 'index':
				# check if template exists
				if(!$this->templateExists($config['website_template'])) throw new Exception('The chosen template cannot be loaded ('.$config['website_template'].').');

				# load template
				include(__PATH_TEMPLATES__ . $config['website_template'] . '/index.php');
				include(__ROOT_DIR__ . 'includes/anti-inspect.php');

				# show admincp button
				if(isLoggedIn() && canAccessAdminCP($_SESSION['username'])) {
					echo '<a href="'.__PATH_ADMINCP_HOME__.'" class="btn btn-primary admincp-button">AdminCP</a>';
				}
				break;

			case 'api':
				break;

			case 'cron':
				break;

			case 'admincp':
				break;

			case 'install':
				break;

			default:
				throw new Exception('Access forbidden.');
		}
	}

	public function loadModule($page = 'news',$subpage = 'home') {
		global $config,$lang,$custom,$mconfig,$tSettings;

		try {
			$handler = $this;

			// Sanear $page / $subpage con guardas contra null
			$page    = $this->cleanRequest($page ?? '');
			$subpage = $this->cleanRequest($subpage ?? '');

			// ---- FIX: $_GET['request'] puede no existir / ser null
			$requestRaw = $_GET['request'] ?? '';
			if(is_string($requestRaw) && $requestRaw !== '') {
				$request = explode("/", $requestRaw);
				if(is_array($request)) {
					// recorre en pares: key/value
					for($i = 0, $n = count($request); $i < $n; $i += 2) {
						$keyRaw = $request[$i] ?? '';
						$valRaw = $request[$i+1] ?? '';

						$key = $this->cleanRequest($keyRaw);
						if($key === '') continue;

						// ---- FIX: reemplazo de FILTER_SANITIZE_STRING (deprecated)
						$val = $this->sanitizeValue($valRaw);
						$_GET[$key] = $val;
					}
				}
			}
			// ---- END FIX

			if(!check_value($page)) { $page = 'news'; }

			if(!check_value($subpage)) {
				if($this->moduleExists($page)) {
					@loadModuleConfigs($page);
					include(__PATH_MODULES__ . $page . '.php');
				} else {
					$this->module404();
				}
			} else {
				// HANDLE PAGE AS DIRECTORY (PATH)
				switch($page) {
					case 'news':
						if($this->moduleExists($page)) {
							@loadModuleConfigs($page);
							include(__PATH_MODULES__ . $page . '.php');
						} else {
							$this->module404();
						}
					break;

					case 'guides':
						if($this->moduleExists($page)) {
							@loadModuleConfigs($page);
							include(__PATH_MODULES__ . $page . '.php');
						} else {
							$this->module404();
						}
					break;

					default:
						$path = $page.'/'.$subpage;
						if($this->moduleExists($path)) {
							$cnf = $page.'.'.$subpage;
							@loadModuleConfigs($cnf);
							include(__PATH_MODULES__ . $path . '.php');
						} else {
							$this->module404();
						}
					break;
				}
			}
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}

	private function moduleExists($page) {
		return file_exists(__PATH_MODULES__ . $page . '.php');
	}

	private function usercpmoduleExists($page) {
		return file_exists(__PATH_MODULES_USERCP__ . $page . '.php');
	}

	private function templateExists($template) {
		return file_exists(__PATH_TEMPLATES__ . $template . '/index.php');
	}

	private function languageExists($language) {
		return file_exists(__PATH_LANGUAGES__ . $language . '/language.php');
	}

	private function admincpmoduleExists($page) {
		return file_exists(__PATH_ADMINCP_MODULES__ . $page . '.php');
	}

	public function webenginePowered() {
		if($this->_disableWebEngineFooterCredits) return;

		echo '<a href="https://webenginecms.org/" target="_blank" class="webengine-powered">';
			echo 'Powered by WebEngine';
			if(!$this->_disableWebEngineFooterVersion) echo ' ' . __WEBENGINE_VERSION__;
		echo '</a>';
	}

	public function loadAdminCPModule($module='home') {
		global $config,$lang,$custom,$handler,$mconfig,$gconfig,$webengine;

		$dB = Connection::Database('MuOnline');
		$dB2 = Connection::Database('Me_MuOnline');
		$common = new common();

		$module = (check_value($module) ? $module : 'home');
		if($this->admincpmoduleExists($module)) {
			include(__PATH_ADMINCP_MODULES__.$module.'.php');
		} else {
			message('error','INVALID MODULE');
		}
	}

	public function websiteTitle() {
		$websiteTitle = (check_value(lang('website_title',true)) && lang('website_title',true) != 'ERROR' ? lang('website_title',true) : config('website_title',true));
		echo $websiteTitle;
	}

	// ---- FIX: evitar preg_replace(null, ...)
	private function cleanRequest($string) {
		$string = (string)$string; // cast seguro
		$out = preg_replace("/[^a-zA-Z0-9\s\/]/", "", $string);
		return $out ?? '';
	}

	// ---- Nuevo: sanitizador de valores (reemplaza FILTER_SANITIZE_STRING)
	private function sanitizeValue($value) {
		$value = (string)$value;
		// quita etiquetas
		$value = strip_tags($value);
		// permite letras/números/espacios y caracteres comunes (-_.@:,/)
		$value = preg_replace('/[^\pL\pN\s\-\_\.\@\:\,\/]/u', '', $value);
		// normaliza espacios
		$value = preg_replace('/\s+/u', ' ', $value);
		return trim($value);
	}

	private function module404() {
		redirect();
	}

	public function switchLanguage($language) {
		if(!check_value($language)) return;
		if(!$this->languageExists($language)) return;

		# set session variable
		$_SESSION['language_display'] = $language;

		return true;
	}

	private function _loadLanguagePhrases($language='en') {
		global $lang;
		if(!@include_once(__PATH_LANGUAGES__ . $language . '/language.php')) throw new Exception('Language phrases could not be loaded ('.$language.').');
	}

	public function checkWebEngineBlacklist() {
		$url = 'http://version.webenginecms.org/1.0/blacklist.php';
		$fields = array(
			'baseurl' => urlencode(__BASE_URL__),
		);
		$fieldsArray = [];
		foreach($fields as $key => $value) {
			$fieldsArray[] = $key . '=' . $value;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, implode("&", $fieldsArray));
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'WebEngine');
		curl_setopt($ch, CURLOPT_HEADER, false);
		$result = curl_exec($ch);
		curl_close($ch);
		if(!$result) return;
		$resultArray = json_decode($result, true);
		if(!is_array($resultArray)) return;
		if(!array_key_exists('blacklisted', $resultArray)) return;
		if($resultArray['blacklisted'] === 1) {
			$webengineConfigurations = webengineConfigs();
			$webengineConfigurations['blacklisted'] = true;
			$newWebEngineConfig = json_encode($webengineConfigurations, JSON_PRETTY_PRINT);
			$cfgFile = fopen(__PATH_CONFIGS__.'webengine.json', 'w');
			if(!$cfgFile) return;
			if(!fwrite($cfgFile, $newWebEngineConfig)) return;
			fclose($cfgFile);
		}
		return;
	}

	public function versionApiListener() {
		if(!array_key_exists('HTTP_USER_AGENT', $_SERVER)) return;
		if($_SERVER['HTTP_USER_AGENT'] != "WebEngine") return;
		$this->checkWebEngineBlacklist();
	}
}
