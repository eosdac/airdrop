<?php
$lang = 'en';
$language_files = array_diff(scandir('./lang'), array('.', '..'));
if (isset($_POST['lang']) && in_array($_POST['lang'] . '.json', $language_files)) {
    $lang = $_POST['lang'];
}
$supported_languages = array();
$strings = array();
foreach($language_files as $language_file) {
    // note: This is safe because $language_files can only be set via scandir('./lang')
    $language_file_json = file_get_contents('./lang/' . $language_file);
    $langage_data = json_decode($language_file_json, true);
    reset($langage_data);
    $langauge_code = key($langage_data);
    $supported_languages[$langauge_code] = $langage_data[$langauge_code]['language'];
    if ($lang == $langauge_code) {
        $strings = $langage_data[$langauge_code];
    }
}
