<?php

namespace App\Http\Controllers;

Trait TraitChavesSaft{

    public function pegarChavePrivada()
    {
        $privatekey = "MIICXQIBAAKBgQDlXVtFxC1Il/IA0ikvRYXTVT8YxIBjWbeU7y3SdTVgTmvqjkquS5Jh+VM+lYv/41wmtB2+/XmppGiSEO+4RuG7D+EVgukQ97sDWqx1OjpGmq+aViBXlgjgId1iXiL31YdWq/62NRuv978dsIPajIgr6scQLTkyBJfotjV7nRah4wIDAQABAoGAN/GiXozwAqHVDDA2jWomrxo+zpq3OgRbC7+e7JNcFoZqOgbE3mheZvk6PayaPirFgkcybjBDKYaJXv80iTz4t4J5LFXtJs450zHnkrY36XlLSe1p+QGnyNx3i8n1JDz2VEmC34AtkpCxgGMkZ2wIuHvzRwjAQ8KIoIkGjNb360kCQQD2mq1urmyXXa6taYv7RpbJLGKLVX0qn/6jsh1F/ReckeJutVb7u4PKf+NqNXdhXtKt0wtgzmwmrixM/Oqawsf/AkEA7hqHSmqD6G8d4LSPTxXFjCfExu8zV0QiVWd/jpy0QnZvW30FK77nzeyvWZ+nj8wuUriu8Qnj0XJ4l86pS6oGHQJBANi0bknAH48YdSLQiIFks6bPST21/0sQ1B0XrV/OnAwrqrasxmZqjtLJdZfkqia3xB2aQvpsC2AmWKnC64raNhMCQCxiw5+qtYZJ2H8ACcsLWvUioLsY8jAtYl0bWxsBuVS+cUnTx3f9MYcgvRtu+LSEson3JZ2HY3Gy7ioWe1bAjj0CQQDgYKL5xpYoBAg6dSpgZywksx+L4hAI99UGNcirWhM7gSNtTV8Lt7X9fNPBMkrKZBBedHkDTEEMtzeUPOTdgwD2";

        return $privatekey;
    }
    public function pegarChavePublica()
    {
        $publickey = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDlXVtFxC1Il/IA0ikvRYXTVT8YxIBjWbeU7y3SdTVgTmvqjkquS5Jh+VM+lYv/41wmtB2+/XmppGiSEO+4RuG7D+EVgukQ97sDWqx1OjpGmq+aViBXlgjgId1iXiL31YdWq/62NRuv978dsIPajIgr6scQLTkyBJfotjV7nRah4wIDAQAB";
        return $publickey;
    }
    
}