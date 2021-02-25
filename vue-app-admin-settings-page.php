<?php

class SettingsForVuePlugin {

    // 1 - identify the name of the plugin
    private $pluginName = "vue-jokes";
    private $pluginSettingsName = "vue-jokes-settings-page";

    // 2 - Create the Form's UI
    // and name the page
    public function displaySettingsPage() {
        ?>
            <form method="POST" action="options.php">
                <?php
                settings_fields($this->pluginName);
                do_settings_sections($this->pluginSettingsName);
                submit_button();
                ?>
            </form>
        <?php
    }

    // 3 - Add it to WordPress, so it can be seen as a option
    public function addVueJokesAdminOptions() {
        $pageTitle = "Vue Jokes | Settings Page";
        $menuTitle = "Vue Jokes";

        // https://developer.wordpress.org/reference/functions/add_options_page/
        // add_options_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', int $position = null )
        add_options_page(
            $pageTitle,
            $menuTitle,
            "manage_options",
            $this->pluginName,
            [$this, "displaySettingsPage"]
        );
    }

    // 4 - create the section Text
    public function sectionTextForJokeOptions() {
        echo '<p>Check the types of jokes you want.</p>';
    }

    public function sectionTextForAPI() {
        echo '<p>Change the API settings here.</p>';
    }


    // 5 - Register the fields
    // Also so when we hit save, it saves it in the WP Database
    public function saveVueJokesAdminOptions() {
        register_setting(
            $this->pluginName,
            $this->pluginName,
            [$this, "validatePluginOptions"]
        );

        // two sections

        // TODO: Maybe Separate them as separate sections/items?

        // adds a section with a description. 
        // add_settings_section( string $id, string $title, callable $callback, string $page )
        add_settings_section(
            $this->pluginName . "-joke-options",
            "Joke Category Settings",
            [$this, "sectionTextForJokeOptions"],
            $this->pluginSettingsName,
        );

        // adds the field itself
        // add_settings_field( string $id, string $title, callable $callback, string $page,
        //                     string $section = 'default', array $args = array() )
        // category --> checkboxes 
        add_settings_field(
            "joke_category",
            "Joke Category (Show)",
            [$this, "inputJokeOptionsCategory"], // has to be a callback
            $this->pluginSettingsName,
            $this->pluginName . "-joke-options"
        );

        // blacklist --> checkboxes 
        add_settings_field(
            "joke_blacklist",
            "Joke Blacklist (Remove)",
            [$this, "inputJokeOptionsBlacklist"],
            $this->pluginSettingsName,
            $this->pluginName . "-joke-options"
        );

        add_settings_section(
            $this->pluginName . "-api",
            "Joke API Settings",
            [$this, "sectionTextForAPI"],
            $this->pluginSettingsName,
        );

        // joketype --> radio (one, other, or both)
        add_settings_field(
            "joke_type",
            "Joke Type",
            [$this, "inputAPIJokeType"],
            $this->pluginSettingsName,
            $this->pluginName . "-api",
        );

        // jokerange -> input 1
        add_settings_field(
            "joke-range1",
            "Jokes - From:",
            [$this, "inputAPIJokeRange1"],
            $this->pluginSettingsName,
            $this->pluginName . "-api"
        );

        // jokerange -> input 2
        add_settings_field(
            "joke-range2",
            "Jokes - To:",
            [$this, "inputAPIJokeRange2"],
            $this->pluginSettingsName,
            $this->pluginName . "-api"
        );
    }

    // 6 - Create the inputs
    public function inputJokeOptionsCategory() {

        $categoryList = [
            "programming",
            "miscellaneous",
            "pun",
            "spooky",
            "christmas"
        ];

        $options = get_option($this->pluginName);

        $html = '';

        foreach ($categoryList as $item) {
            $id = $this->pluginName . "[category-$item]";

            $checked = ($options["category-$item"]) ? ' checked="checked"  ' : '';

            $html .= "<label><input $checked id='$id' name='$id' type='checkbox' />$item</label><br />";
        };
        echo $html;
    }

    public function inputJokeOptionsBlacklist() {
        $options = get_option($this->pluginName);

        $blackList = [
            "nsfw",
            "religious",
            "political",
            "racist",
            "sexist"
        ];

        $html = '';

        foreach ($blackList as $item) {
            $id = $this->pluginName . "[blacklist-$item]";

            $checked = ($options["blacklist-$item"]) ? ' checked="checked"  ' : '';

            $html .= "<label><input $checked id='$id' name='$id' type='checkbox' />$item</label><br />";
        };

        echo $html;
    }


    public function inputAPIJokeType() {
        $options = get_option($this->pluginName);
        $inputName = $this->pluginName . '[joketype]';

        $choices = ["any", "single", "twopart"];
        $html = "";

        foreach ($choices as $choice) {
            $checked = ($options['joketype'] === $choice) ? ' checked="checked" ' : '';
            $html .= "<label><input " . $checked . " value='$choice' name='$inputName' type='radio' /> $choice</label><br />";
        }


        echo "<fieldset>$html</fieldset>";
    }

    public function inputAPIJokeRange1() {
        $options = get_option($this->pluginName);
        echo "
                <input 
                    id='$this->pluginName[joke-range1]'
                    name='$this->pluginName[joke-range1]'
                    size='20'
                    type='text'
                    value='{$options['joke-range1']}'
                    placeholder='0'
                />
            ";
    }

    public function inputAPIJokeRange2() {
        $options = get_option($this->pluginName);
        echo "
                <input 
                    id='$this->pluginName[joke-range2]'
                    name='$this->pluginName[joke-range2]'
                    size='20'
                    type='text'
                    value='{$options['joke-range2']}'
                    placeholder='100'
                />
            ";
    }


    // 7 - Validate/Sanitize text 
    public function validatePluginOptions($input) {

        $formElements = [
            "category-programming",
            "category-miscellaneous",
            "category-pun",
            "category-spooky",
            "category-christmas",
            "blacklist-nsfw",
            "blacklist-religious",
            "blacklist-political",
            "blacklist-racist",
            "blacklist-sexist",
            'joketype',
            'joke-range1',
            'joke-range2'
        ];

        foreach ($formElements as $element) {
            $newinput[$element] = trim($input[$element]);
        }

        return $newinput;
    }
}
