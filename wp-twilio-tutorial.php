<?php


class Sendex
{
    // 1 - identify the name of the plugin
    public $pluginName = "sendex";
    public $pluginSettingsName = "sendex-settings-page";

    // 2 - Create the Form's UI
    // and name the page
    public function displaySendexSettingsPage()
    {     
        ?>
            <form method="POST" action="options.php">
                <?php 
                    settings_fields($this->pluginName);
                    // do_settings_sections('sendex-settings-page');
                    do_settings_sections($this->pluginSettingsName);
                    submit_button();
                ?>
            </form>
        <?php
    }

    // 3 - Add it to WordPress, so it can be seen as a option
    public function addSendexAdminOption()
    {

        add_options_page(
            "SENDEX SMS PAGE",
            "SENDEX",
            "manage_options",
            $this->pluginName,
            [$this, "displaySendexSettingsPage"]
        );
    }


    // 4 - Register the fields
    // Also so when we hit save, it saves it in the WP Database
    public function sendexAdminSettingsSave()
    {

        register_setting(
            $this->pluginName,
            $this->pluginName,
            [$this, "pluginOptionsValidate"]
        );

        // adds a section with a description. 
        // add_settings_section( string $id, string $title, callable $callback, string $page )
        add_settings_section(
            "sendex-main",
            "Main Settings",
            [$this, "sendexSectionText"],
            $this->pluginSettingsName,
        );

        // adds the field itself
        // add_settings_field( string $id, string $title, callable $callback, string $page,
        //                     string $section = 'default', array $args = array() )
        add_settings_field(
            "api_sid",
            "API SID",
            [$this, "sendexSettingSid"],
            $this->pluginSettingsName,
            "sendex-main"
        );

        add_settings_field(
            "api_auth_token",
            "API AUTH TOKEN",
            [$this, "sendexSettingToken"],
            $this->pluginSettingsName,
            "sendex-main"
        );
    }


    // 
    public function sendexSectionText()
    {
        echo '<h3 style="text-decoration: underline;">Edit api details</h3>';
    }

    /**
     * Renders the sid input field
     *  @since    1.0.0
     */
    public function sendexSettingSid() {

        $options = get_option($this->pluginName);
        echo "
            <input 
                id='$this->pluginName[api_sid]'
                name='$this->pluginName[api_sid]'
                size='40'
                type='text'
                value='{$options['api_sid']}'
                placeholder='Enter your API SID here'
            />
        ";
    }

      /**
     * Renders the auth_token input field
     */
    public function sendexSettingToken() {

        $options = get_option($this->pluginName);
        echo "
            <input 
                id='$this->pluginName[api_auth_token]'
                name='$this->pluginName[api_auth_token]'
                size='40'
                type='text'
                value='{$options['api_auth_token']}'
                placeholder='Enter your API Auth Token here'
            />
        ";
    }

    public function pluginOptionsValidate($input) {

        $newinput["api_sid"] = trim($input["api_sid"]);
        $newinput["api_auth_token"] = trim($input["api_auth_token"]);

        return $newinput;
    }

}
