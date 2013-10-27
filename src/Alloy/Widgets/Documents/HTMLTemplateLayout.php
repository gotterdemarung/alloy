<?php

namespace PHPocket\Widgets\Documents;


class HTMLTemplateLayout extends HTMLTemplate
{

    public function show($final = true, $charset = 'utf-8')
    {
        if (!headers_sent()) {
            header('Content-Type: text/html; charset=' . $charset);
        }

        echo $this->getValue(self::HTML_FULL);

        if ($final) {
            exit;
        }
    }

}