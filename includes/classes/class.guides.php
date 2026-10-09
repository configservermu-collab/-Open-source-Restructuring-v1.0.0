<?php
class Guides {

    private $guidesDir;
    private $cacheDir;
    private $id = null;
    private $language = null;

    public function __construct() {
        $this->guidesDir = __DIR__ . '/../../guides/';
        $this->cacheDir  = __DIR__ . '/../../includes/cache/guides/';

        if (!is_dir($this->guidesDir)) @mkdir($this->guidesDir, 0777, true);
        if (!is_dir($this->cacheDir))  @mkdir($this->cacheDir, 0777, true);
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setLanguage(string $lang): void {
        $this->language = strtolower($lang);
    }

    public function getGuideData(): ?array {
        $file = $this->guidesDir . $this->id . '.json';
        return file_exists($file) ? json_decode(file_get_contents($file), true) : null;
    }

    public function saveGuide(array $data): bool {
        if (!isset($data['guides_id'])) return false;
        $file = $this->guidesDir . intval($data['guides_id']) . '.json';
        return (bool) file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    // === Nuevo: compat con módulo addguides.php ===
    public function addGuides(string $title, string $content, string $author = 'Administrator', int $status = 0): bool {
        $id = $this->getNextId();
        $now = date('Y-m-d H:i:s');

        $data = [
            'guides_id'      => $id,
            'guides_title'   => $title,
            'guides_content' => $content,
            'guides_author'  => $author ?: 'Administrator',
            'guides_status'  => $status,           // 0: publicado/borrador según tu uso
            'created_at'     => $now,
            'updated_at'     => $now,
            // Estructura opcional para traducciones si la usás
            'translations'   => [],
        ];

        return $this->saveGuide($data);
    }

    public function deleteGuide(): bool {
        $file = $this->guidesDir . $this->id . '.json';
        return file_exists($file) ? unlink($file) : false;
    }

    public function getAllGuides(): array {
        $list = [];
        foreach (glob($this->guidesDir . '*.json') as $file) {
            $data = json_decode(file_get_contents($file), true);
            if (isset($data['guides_id'])) $list[] = $data;
        }
        // opcional: orden por id desc
        usort($list, function($a,$b){ return ($b['guides_id'] ?? 0) <=> ($a['guides_id'] ?? 0); });
        return $list;
    }

    public function deleteTranslation(): bool {
        $file = $this->guidesDir . $this->id . '.json';
        if (!file_exists($file)) return false;
        $data = json_decode(file_get_contents($file), true);
        if (!isset($data['translations'][$this->language])) return false;
        unset($data['translations'][$this->language]);
        return (bool) file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function isGuidesDirWritable(): bool {
        return is_writable($this->guidesDir);
    }

    public function translationExists(): bool {
        $data = $this->getGuideData();
        return isset($data['translations'][$this->language]);
    }

    public function getTranslation(): ?array {
        $data = $this->getGuideData();
        return $data['translations'][$this->language] ?? null;
    }

    public function addOrUpdateTranslation(string $title, string $content): bool {
        $data = $this->getGuideData();
        if (!$data) return false;
        $data['translations'][$this->language] = [
            'guides_title'   => $title,
            'guides_content' => $content
        ];
        $data['updated_at'] = date('Y-m-d H:i:s');
        return (bool) file_put_contents($this->guidesDir . $this->id . '.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    // === Compatibilidad con el módulo: métodos de caché llamados tras guardar ===
    public function cacheGuides(): void {
        // Genera un índice simple de guías para uso rápido
        $index = [];
        foreach ($this->getAllGuides() as $g) {
            $index[] = [
                'guides_id'     => $g['guides_id'] ?? null,
                'guides_title'  => $g['guides_title'] ?? '',
                'guides_author' => $g['guides_author'] ?? '',
                'guides_status' => $g['guides_status'] ?? 0,
                'created_at'    => $g['created_at'] ?? null,
                'updated_at'    => $g['updated_at'] ?? null,
            ];
        }
        @file_put_contents($this->cacheDir . 'guides.index.json', json_encode($index, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function updateGuidesCacheIndex(): void {
        // Alias a cacheGuides() para compatibilidad
        $this->cacheGuides();
    }

    public static function loadConfig(): array {
        $configFile = __DIR__ . '/../../includes/config/webengine.json';
        if (!file_exists($configFile)) return [];
        return json_decode(file_get_contents($configFile), true);
    }

    public static function getModuleConfig(string $key, $default = null) {
        $config = self::loadConfig();
        return $config['guides_module'][$key] ?? $default;
    }

    // === Helpers privados ===
    private function getNextId(): int {
        $max = 0;
        foreach (glob($this->guidesDir . '*.json') as $file) {
            $name = basename($file, '.json');
            $num  = intval($name);
            if ($num > $max) $max = $num;
        }
        return $max + 1;
    }
}
