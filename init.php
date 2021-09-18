<?php
class af_refspoof extends Plugin {
    /** @var PluginHost **/
    protected $host;

    /** @var Db **/
    protected $dbh;

    function about()
    {
        return array(
            "2.0.0",
            "Fakes Referral on Images",
            "Alexander Chernov"
            );
    }

    /**
    * Init
    *
    * @param PluginHost $host
    */
    function init($host)
    {
        $this->host = $host;
        $this->dbh = Db::pdo();
        $host->add_hook($host::HOOK_ARTICLE_FILTER, $this);
        $host->add_hook($host::HOOK_PREFS_TAB, $this);
    }

    /**
    * Preference tab hook
    *
    * @param mixed $args
    */
    function hook_prefs_tab($args)
    {
        if ($args != "" && $args != "prefPrefs"){
            return;
        }
        $configFeeds = $this->host->get($this,"feeds");
        $feeds = $this->getFeeds();

        print <<<EOF
        <div id="refSpoofConfigTab" dojoType="dijit.layout.ContentPane" title="{$this->translate('Plugin RefSpoof')}" style="overflow:auto">
EOF;
        if (count($feeds)){
            //table header
            print <<<EOF
            <form dojoType="dijit.form.Form" style="width:95%">
            <input dojoType="dijit.form.TextBox" style="display : none" name="op" value="pluginhandler">
            <input dojoType="dijit.form.TextBox" style="display : none" name="method" value="saveConfig">
            <input dojoType="dijit.form.TextBox" style="display : none" name="plugin" value="af_refspoof">

            <script type="dojo/method" event="onSubmit" args="evt">
                evt.preventDefault();
                if (this.validate()) {
                    xhr.post("backend.php", this.getValues(), (reply) => {
                        Notify.info(reply);
                    })
                }
                </script>
            <table>
                <tr>
                    <th>{$this->translate("Feed Name")}</th>
                    <th></th>
                </tr>
EOF;
            foreach ($feeds as $feed){
                $checked = "";
                if (isset($configFeeds[$feed->id])){
                    $checked = "checked='checked'";
                }
                print <<<EOF
                <tr>
                    <td colspan="2">
                        <input dojoType="dijit.form.CheckBox" type="checkbox" name="refSpoofFeed[{$feed->id}]" id="refSpoofFeed_{$feed->id}" value="1" {$checked}>{$feed->title}
                    </td>
                </tr>
EOF;
            }
            print <<<EOF
            <tr>
                <td></td>
                <td><button dojoType="dijit.form.Button" type="submit">{$this->translate("Save")}</button></td>
            </tr>
EOF;
            print "</table>";
            print "</form>";
        }
        print "</div>";
    }

    function make_redirect_url($url, $ref)
    {
        return $this->host->get_public_method_url($this, "spoof",
            ["refspoof_url" => urlencode($url), "refspoof_ref" => urlencode($ref)]);
    }

    private function processArticle($article)
    {
        $need_saving = false;

        $doc = new DOMDocument();
        if (@$doc->loadHTML('<?xml encoding="UTF-8">' . $article["content"])) {
            $xpath = new DOMXPath($doc);
            $imgs = $xpath->query("//img[@src]");

            foreach ($imgs as $img) {
                $orig_src = $img->getAttribute("src");
                $new_src = $this->make_redirect_url($orig_src, $article['link']);

                if ($new_src != $orig_src) {
                    $need_saving = true;

                    $img->setAttribute("src", $new_src);
                    $img->removeAttribute("srcset");
                }
            }
        }

        if ($need_saving)
            $article["content"] = $doc->saveHTML();

        return $article;
    }

    function hook_article_filter($article)
    {
        $feedID = $article['feed']['id'];
        $feeds  = $this->host->get($this, 'feeds');

        if (is_array($feeds) && in_array($feedID,array_keys($feeds))){
            $article = $this->processArticle($article);
        }
        return $article;
    }

    function is_public_method($method)
    {
        return $method === "spoof";
    }

    function spoof()
    {
        $url = urldecode($_REQUEST["refspoof_url"]);
        $ref = urldecode($_REQUEST["refspoof_ref"]);

        ob_end_clean(); // this appears to make echo work?

        if(empty($url) || empty($ref)) {
            http_response_code(400);
            echo "need refspoof_url and refsppof_ref arguments";
            return;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $ref);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $result = curl_exec($ch);
        curl_close($ch);

        //$client->loadCommonSettings();
        //$client->setUserAgent();

        header("Content-Type: ". $contentType);
        echo $result;
        exit(0);
    }

    function saveConfig()
    {
        $config = (array) $_POST['refSpoofFeed'];
        $this->host->set($this, 'feeds', $config);
        echo __("Configuration saved.");
    }

    protected function translate($msg)
    {
        return __($msg);
    }

    /**
    * Find feeds from db
    *
    * @return array feeds
    */
    protected function getFeeds()
    {
        $feeds = array();
        $stmt = $this->dbh->prepare("SELECT id, title
                FROM ttrss_feeds
                WHERE owner_uid = :sessionID
                ORDER BY order_id, title");
        $stmt->execute(['sessionID' => $_SESSION["uid"]]);
        $result = $stmt->fetch();
        while ($line = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $feeds[] = (object) $line;
        }
        return $feeds;
    }

    function api_version()
    {
        return 2;
    }
}
