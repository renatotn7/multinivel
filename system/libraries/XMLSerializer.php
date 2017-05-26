<?php

class XMLSerializer {

    /**
     * 
     * The most advanced method of serialization.
     * 
     * @param mixed $obj => can be an objectm, an array or string. may contain unlimited number of subobjects and subarrays
     * @param string $wrapper => main wrapper for the xml
     * @param array (key=>value) $replacements => an array with variable and object name replacements
     * @param boolean $add_header => whether to add header to the xml string
     * @param array (key=>value) $header_params => array with additional xml tag params
     * @param string $node_name => tag name in case of numeric array key
     */
    public static function generateValidXmlFromMixiedObj($obj, $wrapper = null, $replacements = array(), $add_header = true, $header_params = array(), $node_name = 'node') {
        $xml = '';
        if ($add_header)
            $xml .= self::generateHeader($header_params);
        if ($wrapper != null)
            $xml .= '<' . $wrapper . '>';
        if (is_object($obj)) {
            $node_block = strtolower(get_class($obj));
            if (isset($replacements[$node_block]))
                $node_block = $replacements[$node_block];
            $xml .= '<' . $node_block . '>';
            $vars = get_object_vars($obj);
            if (!empty($vars)) {
                foreach ($vars as $var_id => $var) {
                    if (isset($replacements[$var_id]))
                        $var_id = $replacements[$var_id];
                    $xml .= '<' . $var_id . '>';
                    $xml .= self::generateValidXmlFromMixiedObj($var, null, $replacements, false, null, $node_name);
                    $xml .= '</' . $var_id . '>';
                }
            }
            $xml .= '</' . $node_block . '>';
        }
        else if (is_array($obj)) {
            foreach ($obj as $var_id => $var) {
                if (!is_object($var)) {
                    if (is_numeric($var_id))
                        $var_id = $node_name;
                    if (isset($replacements[$var_id]))
                        $var_id = $replacements[$var_id];
                    $xml .= '<' . $var_id . '>';
                }
                $xml .= self::generateValidXmlFromMixiedObj($var, null, $replacements, false, null, $node_name);
                if (!is_object($var))
                    $xml .= '</' . $var_id . '>';
            }
        }
        else {
            $xml .= htmlspecialchars($obj, ENT_QUOTES);
        }

        if ($wrapper != null)
            $xml .= '</' . $wrapper . '>';

        return $xml;
    }

    /**
     * 
     * xml header generator
     * @param array $params
     */
    public static function generateHeader($params = array()) {
        $basic_params = array('version' => '1.0', 'encoding' => 'UTF-8');
        if (!empty($params))
            $basic_params = array_merge($basic_params, $params);

        $header = '<?xml';
        foreach ($basic_params as $k => $v) {
            $header .= ' ' . $k . '=' . $v;
        }
        $header .= ' ?>';
        return $header;
    }

}
