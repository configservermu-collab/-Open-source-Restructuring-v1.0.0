<?php
/**
 * Widgets Helper - WebEngine CMS
 * Sistema de widgets para template default usando rankings cacheados
 * No requiere consultas SQL adicionales
 */

// Cargar configuración de widgets
function loadWidgetsConfig(): array {
    $rootPath = dirname(__DIR__);
    $configFile = $rootPath . '/admincp/data/widgets_config.json';
    $defaultConfig = [
        'wanted_widget' => [
            'enabled' => true,
            'title' => 'WANTED',
            'min_pk_count' => 1,
            'rank_berserker' => 100,
            'rank_killer' => 50,
            'rank_hunter' => 20,
            'background_color' => '#8B0000,#DC143C',
            'text_color' => '#FFD700'
        ],
        'vicio_widget' => [
            'enabled' => true,
            'title' => 'EL MÁS VICIO',
            'criteria' => 'level',
            'background_color' => '#4B0082,#8A2BE2',
            'text_color' => '#FFD700'
        ],
        'online_widget' => [
            'enabled' => true,
            'title' => 'USUARIOS ONLINE',
            'refresh_interval' => 20,
            'fake_count' => 0,
            'background_color' => '#006400,#32CD32',
            'text_color' => '#FFD700',
            'variant' => 'badge'
        ],
        'general' => [
            'enabled' => true,
            'show_class_images' => true,
            'show_status' => true,
            'widget_effects' => true,
            'position' => 'hero'
        ]
    ];

    if (file_exists($configFile)) {
        $config = json_decode(file_get_contents($configFile), true);
        if (is_array($config)) {
            return array_replace_recursive($defaultConfig, $config);
        }
    }

    return $defaultConfig;
}

// Función de compatibilidad para cargar caché (widgets)
function loadWidgetCache($cacheFile): array {
    $rootPath = dirname(dirname(__FILE__));
    $cachePath = $rootPath . '/includes/cache/' . $cacheFile;
    
    if (!file_exists($cachePath)) {
        return [];
    }
    
    $content = file_get_contents($cachePath);
    if (empty($content)) {
        return [];
    }
    
    // WebEngine usa formato texto plano con líneas separadas
    $lines = explode("\n", trim($content));
    $result = [];
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        // Cada línea: Name¦Class¦PkCount¦Level¦Reset¦MasterLevel
        $parts = explode('¦', $line);
        if (count($parts) >= 3) {
            $result[] = [
                'Name' => $parts[0] ?? '',
                'Class' => (int)($parts[1] ?? 0),
                'PkCount' => (int)($parts[2] ?? 0),
                'cLevel' => (int)($parts[3] ?? 0),
                'Reset' => (int)($parts[4] ?? 0),
                'MasterLevel' => (int)($parts[5] ?? 0)
            ];
        }
    }
    
    return $result;
}

// Obtener jugador con más PKs desde el ranking cacheado
function getTopPKPlayerFromCache(): array {
    $rankingData = loadWidgetCache('rankings_pk.cache');
    if (empty($rankingData)) {
        return [
            'Name' => 'Sin datos',
            'PkCount' => 0,
            'Class' => 0,
            'cLevel' => 0
        ];
    }

    // Tomar el primer jugador (ya viene ordenado por PKs)
    $topPlayer = $rankingData[0];
    
    return [
        'Name' => $topPlayer['Name'] ?? 'Sin datos',
        'Class' => $topPlayer['Class'] ?? 0,
        'PkCount' => $topPlayer['PkCount'] ?? 0,
        'cLevel' => $topPlayer['cLevel'] ?? 0
    ];
}

// Obtener jugador con más nivel desde el ranking cacheado
function getTopLevelPlayerFromCache(): array {
    $rankingData = loadWidgetCache('rankings_level.cache');
    if (empty($rankingData)) {
        return [
            'Name' => 'Sin datos',
            'cLevel' => 0,
            'Class' => 0
        ];
    }

    // Tomar el primer jugador (ya viene ordenado por nivel)
    $topPlayer = $rankingData[0];

    return [
        'Name' => $topPlayer['Name'] ?? 'Sin datos',
        'Class' => $topPlayer['Class'] ?? 0,
        'cLevel' => $topPlayer['cLevel'] ?? 0
    ];
}

// Obtener cantidad de usuarios online desde el ranking cacheado
function getOnlineCountFromCache(int $fakeCount = 0): int {
    $rootPath = dirname(dirname(__FILE__));
    $cachePath = $rootPath . '/includes/cache/online_characters.cache';
    
    if (!file_exists($cachePath)) {
        return $fakeCount;
    }
    
    $content = file_get_contents($cachePath);
    $onlineData = json_decode($content, true);
    
    if (!is_array($onlineData)) {
        return $fakeCount;
    }
    
    $realCount = count($onlineData);
    return $realCount + $fakeCount;
}

// Helpers para widgets "time-themed"
if (!function_exists('timeWidgetExtractColors')) {
    function timeWidgetExtractColors(array $config, array $defaults): array {
        $bgColor = $config['background_color'] ?? '';
        if (strpos($bgColor, ',') !== false) {
            $colors = array_map('trim', explode(',', $bgColor, 2));
            return [
                $colors[0] ?? $defaults[0],
                $colors[1] ?? $defaults[1]
            ];
        }
        return [$defaults[0], $defaults[1]];
    }

    function timeWidgetTextColor(array $config, string $default): string {
        return $config['text_color'] ?? $default;
    }

    function timeWidgetOptionEnabled(array $options, string $key, bool $default): bool {
        return isset($options[$key]) ? (bool) $options[$key] : $default;
    }

    function timeWidgetFormatNumber($num): string {
        return number_format((int) $num, 0, '.', ',');
    }
    
    function getClassImageName($classCode): string {
        // Mapeo de códigos de clase a nombres de archivos de imagen
        $classMap = [
            0 => 'dw',      // Dark Wizard
            1 => 'dw',      // Soul Master
            2 => 'dw',      // Grand Master
            16 => 'dk',     // Dark Knight
            17 => 'dk',     // Blade Knight
            18 => 'dk',     // Blade Master
            32 => 'elf',    // Fairy Elf
            33 => 'elf',    // Muse Elf
            34 => 'elf',    // High Elf
            48 => 'mg',     // Magic Gladiator
            49 => 'mg',
            50 => 'mg',
            64 => 'dl',     // Dark Lord
            65 => 'dl',
            66 => 'dl',
            80 => 'sum',    // Summoner
            81 => 'sum',
            82 => 'sum',
            96 => 'rf',     // Rage Fighter
            97 => 'rf',
            98 => 'rf',
            112 => 'gl',    // Grow Lancer
            113 => 'gl',
            114 => 'gl',
            128 => 'rw',    // Rune Wizard
            129 => 'rw',
            130 => 'rw'
        ];
        
        return $classMap[$classCode] ?? 'avatar';
    }

    function renderTimeWantedCard(array $player, array $config, array $options = []): string {
        [$c1, $c2] = timeWidgetExtractColors($config, ['#4a1a1a', '#2d0f0f']);
        $textColor = timeWidgetTextColor($config, '#f1d1d1');
        $borderColor = $config['border_color'] ?? 'rgba(255, 100, 100, 0.4)';

        $ranks = [
            'berserker' => (int) ($config['rank_berserker'] ?? 100),
            'killer' => (int) ($config['rank_killer'] ?? 50),
            'hunter' => (int) ($config['rank_hunter'] ?? 20)
        ];

        $pkCount = (int) ($player['PkCount'] ?? 0);
        $rankLabel = 'CRIMINAL';
        if ($pkCount >= $ranks['berserker']) $rankLabel = 'BERSERKER';
        elseif ($pkCount >= $ranks['killer']) $rankLabel = 'KILLER';
        elseif ($pkCount >= $ranks['hunter']) $rankLabel = 'HUNTER';

        $className = function_exists('returnRaceType') ? returnRaceType($player['Class']) : 'Clase ' . $player['Class'];
        $title = htmlspecialchars($config['title'] ?? 'WANTED', ENT_QUOTES, 'UTF-8');

        $enableEffects = timeWidgetOptionEnabled($options, 'widget_effects', true);
        $cardClass = 'hero-card' . ($enableEffects ? '' : ' no-effects');
        
        // Imagen de clase
        $showClassImg = timeWidgetOptionEnabled($options, 'show_class_images', true);
        $classImgHtml = '';
        if ($showClassImg && isset($player['Class'])) {
            $classImgName = getClassImageName((int)$player['Class']);
            $imgPath = 'templates/default/img/character-avatars/' . $classImgName . '.jpg';
            $classImgHtml = '<div class="class-image"><img src="' . $imgPath . '" alt="' . htmlspecialchars($className, ENT_QUOTES, 'UTF-8') . '" onerror="this.style.display=\'none\'"/></div>';
        }

        $html  = '<div class="' . $cardClass . '" style="background: linear-gradient(135deg,' . $c1 . ',' . $c2 . '); color:' . $textColor . '; border:1px solid ' . $borderColor . ';">';
        $html .= $classImgHtml;
        $html .= '<div class="title">' . $title . '</div>';
        $html .= '<div class="subtitle">' . $rankLabel . '</div>';
        $html .= '<div class="player">' . htmlspecialchars($player['Name'], ENT_QUOTES, 'UTF-8') . '</div>';
        $html .= '<div class="meta">';
        $html .= '<span>Clase: ' . $className . '</span>';
        $html .= '<span>PKs: ' . timeWidgetFormatNumber($pkCount) . '</span>';
        $html .= '<span>Nivel: ' . timeWidgetFormatNumber($player['cLevel']) . '</span>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    function renderTimeVicioCard(array $player, array $config, array $options = []): string {
        [$c1, $c2] = timeWidgetExtractColors($config, ['#2a1a4a', '#180f2d']);
        $textColor = timeWidgetTextColor($config, '#e8d5ff');
        $borderColor = $config['border_color'] ?? 'rgba(180, 130, 255, 0.35)';

        $className = function_exists('returnRaceType') ? returnRaceType($player['Class']) : 'Clase ' . $player['Class'];
        $title = htmlspecialchars($config['title'] ?? 'EL MÁS VICIO', ENT_QUOTES, 'UTF-8');

        $enableEffects = timeWidgetOptionEnabled($options, 'widget_effects', true);
        $cardClass = 'hero-card' . ($enableEffects ? '' : ' no-effects');
        
        // Imagen de clase
        $showClassImg = timeWidgetOptionEnabled($options, 'show_class_images', true);
        $classImgHtml = '';
        if ($showClassImg && isset($player['Class'])) {
            $classImgName = getClassImageName((int)$player['Class']);
            $imgPath = 'templates/default/img/character-avatars/' . $classImgName . '.jpg';
            $classImgHtml = '<div class="class-image"><img src="' . $imgPath . '" alt="' . htmlspecialchars($className, ENT_QUOTES, 'UTF-8') . '" onerror="this.style.display=\'none\'"/></div>';
        }

        $html  = '<div class="' . $cardClass . '" style="background: linear-gradient(135deg,' . $c1 . ',' . $c2 . '); color:' . $textColor . '; border:1px solid ' . $borderColor . ';">';
        $html .= $classImgHtml;
        $html .= '<div class="title">' . $title . '</div>';
        $html .= '<div class="subtitle">ADICTO AL JUEGO</div>';
        $html .= '<div class="player">' . htmlspecialchars($player['Name'], ENT_QUOTES, 'UTF-8') . '</div>';
        $html .= '<div class="meta">';
        $html .= '<span>Clase: ' . $className . '</span>';
        $html .= '<span>Nivel: ' . timeWidgetFormatNumber($player['cLevel']) . '</span>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    function renderTimeOnlineBadge(int $count, array $config, array $options = []): string {
        [$c1, $c2] = timeWidgetExtractColors($config, ['#1a4a2a', '#0f2d18']);
        $textColor = timeWidgetTextColor($config, '#f1e7cf');
        $borderColor = $config['border_color'] ?? 'rgba(255, 221, 162, 0.25)';
        $label = htmlspecialchars($config['title'] ?? 'USUARIOS ONLINE', ENT_QUOTES, 'UTF-8');

        $enableEffects = timeWidgetOptionEnabled($options, 'widget_effects', true);
        $badgeClass = 'hero-badge' . ($enableEffects ? '' : ' no-effects');

        $html  = '<div class="' . $badgeClass . '" style="background: linear-gradient(135deg,' . $c1 . ',' . $c2 . '); color:' . $textColor . '; border:1px solid ' . $borderColor . ';">';
        $html .= '<span class="k">' . $label . '</span>';
        $html .= '<span class="v">' . timeWidgetFormatNumber($count) . '</span>';
        $html .= '</div>';

        return $html;
    }

    function buildTimeWantedWidget(array $config, array $options = []): string {
        if (array_key_exists('enabled', $config) && empty($config['enabled'])) {
            return '';
        }

        // Renderizar SIEMPRE para mostrar placeholder "Sin datos" si no hay PK
        $player = getTopPKPlayerFromCache();

        return renderTimeWantedCard($player, $config, $options);
    }

    function buildTimeVicioWidget(array $config, array $options = []): string {
        if (array_key_exists('enabled', $config) && empty($config['enabled'])) {
            return '';
        }

        $player = getTopLevelPlayerFromCache();
        return renderTimeVicioCard($player, $config, $options);
    }

    function buildTimeOnlineWidget(array $config, array $options = []): string {
        if (array_key_exists('enabled', $config) && empty($config['enabled'])) {
            return '';
        }

        $variant = $options['variant'] ?? ($config['variant'] ?? 'badge');
        $fakeCount = (int)($config['fake_count'] ?? 0);
        $count = getOnlineCountFromCache($fakeCount);

        return renderTimeOnlineBadge($count, $config, $options);
    }
}
?>
