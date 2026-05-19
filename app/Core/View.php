<?php
    namespace App\Core;

    class View {
        public static function render(string $view, array $data = [], string $layout = null): void
        {
            $content = self::capture($view, $data);
            if ($layout) {
                $layoutData = array_merge($data, ['content' => $content]);
                echo self::capture('layout/' . $layout, $layoutData);
            } else {
                echo $content;
            }
        }

        public static function capture(string $name, array $data = []): string
        {
            $file = __DIR__ . '/../View/' . $name . '.php';
            if (!file_exists($file)) {
                throw new \RuntimeException("View tidak ada: {$name}");
            }

            extract($data, EXTR_SKIP);

            ob_start();
            require $file;
            return ob_get_clean();
        }
    }
?>
