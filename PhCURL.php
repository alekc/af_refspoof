<?php
/**
 * Class PhCURL
 *
 * @author Alexander Chernov
 * @version 1.0
 * @see https://github.com/Alekc/PhCurl
 * @license GPL-V2
 */
class PhCURL
{
    /**
    * Curl handle
    *
    * @var resource
    */
    protected $_handle = null;

    protected $_data = null;

    protected $_params = array();

    protected $_url = null;

    /**
    * Flag to indicate that headers are required in output
    *
    * @var boolean
    */
    protected $_headersInOutput = false;

    /**
    * Input headers (server's output)
    *
    * @var string
    */
    protected $_inputHeaders = "";

    /** types of auth **/
    const AUTH_DIGEST   = CURLAUTH_DIGEST;
    const AUTH_BASIC    = CURLAUTH_BASIC;
    const AUTH_GSSNEG   = CURLAUTH_GSSNEGOTIATE ;
    const AUTH_NTLM     = CURLAUTH_NTLM;
    const AUTH_ANY      = CURLAUTH_ANY;
    const AUTH_ANYSAFE  = CURLAUTH_ANYSAFE;

    /** proxy types **/
    const PROXY_HTTP    = CURLPROXY_HTTP;
    const PROXY_SOCKS4  = CURLPROXY_SOCKS4;
    const PROXY_SOCKS5  = CURLPROXY_SOCKS5;

    /** Time Conditions**/
    const TIMECOND_IFMODSINCE   = CURL_TIMECOND_IFMODSINCE;
    const TIMECOND_IFUNMODSINCE = CURL_TIMECOND_IFUNMODSINCE;

    /** SSH AUTH **/
    const SSH_AUTH_PUBLICKEY    = CURLSSH_AUTH_PUBLICKEY;
    const SSH_AUTH_PASSWORD     = CURLSSH_AUTH_PASSWORD;
    const SSH_AUTH_HOST         = CURLSSH_AUTH_HOST;
    const SSH_AUTH_KEYBOARD     = CURLSSH_AUTH_KEYBOARD;
    const SSH_AUTH_ANY          = CURLSSH_AUTH_ANY;

    const METHOD_POST = "post";
    const METHOD_GET  = "get";
    const METHOD_PUT  = "put";

    const CLOSEPOLICY_LEAST_RECENTLY_USED   = CURLCLOSEPOLICY_LEAST_RECENTLY_USED;
    const CLOSEPOLICY_OLDEST                = CURLCLOSEPOLICY_OLDEST;

    const FTPAUTH_DEFAULT   =  CURLFTPAUTH_DEFAULT;
    const FTPAUTH_SSL       =  CURLFTPAUTH_SSL;
    const FTPAUTH_TLS       =  CURLFTPAUTH_TLS ;


    /**
     * Main constructor
     */
    function __construct($url = null)
    {
        if (!empty($url)){
            $this->_url = $url;
        }
        $this->_handle = curl_init($url);
    }

    /**               BOOLEAN SETTINGS                   **/

    /**
     * TRUE to automatically set the Referer: field in requests where it follows a Location: redirect.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableAutoReferer($value)
    {
        curl_setopt($this->_handle,CURLOPT_AUTOREFERER,$value);
        return $this;
    }

    /**
     * TRUE to return the raw output when CURLOPT_RETURNTRANSFER is used.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableBinaryTransfer($value)
    {
        curl_setopt($this->_handle,CURLOPT_BINARYTRANSFER,$value);
        return $this;
    }

    /**
     * TRUE to mark this as a new cookie "session".
     *
     * It will force libcurl to ignore
     * all cookies it is about to load that are "session cookies" from the previous
     * session.
     *
     * By default, libcurl always stores and loads all cookies,
     * independent if they are session cookies or not.
     *
     * Session cookies are cookies without expiry date and they are meant to be
     * alive and existing for this "session" only.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableCookieSession($value)
    {
        curl_setopt($this->_handle,CURLOPT_COOKIESESSION,$value);
        return $this;
    }

    /**
     * TRUE to output SSL certification information to STDERR on secure transfers.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableCertInfo($value)
    {
        curl_setopt($this->_handle,CURLOPT_CERTINFO,$value);
        return $this;
    }

    /**
     * TRUE to convert Unix newlines to CRLF newlines on transfers.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableCrlfConversion($value)
    {
        curl_setopt($this->_handle,CURLOPT_CRLF,$value);
        return $this;
    }

    /**
     * TRUE to use a global DNS cache. This option is not thread-safe and is enabled by default.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableDnsUseGlobalCache($value)
    {
        curl_setopt($this->_handle,CURLOPT_DNS_USE_GLOBAL_CACHE,$value);
        return $this;
    }

    /**
     * TRUE to fail silently if the HTTP code returned is greater than or equal to 400.
     * The default behavior is to return the page normally, ignoring the code.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableFailOnError($value)
    {
        curl_setopt($this->_handle,CURLOPT_FAILONERROR,$value);
        return $this;
    }

    /**
     * TRUE to attempt to retrieve the modification date of the remote document.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableFileTime($value)
    {
        curl_setopt($this->_handle,CURLOPT_FILETIME,$value);
        return $this;
    }

    /**
     * TRUE to follow any "Location: " header that the server sends as part of
     * the HTTP header (note this is recursive, PHP will follow as many
     * "Location: " headers that it is sent, unless CURLOPT_MAXREDIRS is set).
     *
     * @param $value
     * @return PhCURL
     */
    public function enableFollowLocation($value)
    {
        curl_setopt($this->_handle,CURLOPT_FOLLOWLOCATION,$value);
        return $this;
    }

    /**
     * TRUE to force the connection to explicitly close when it has finished processing,
     * and not be pooled for reuse.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableForbidReuse($value)
    {
        curl_setopt($this->_handle,CURLOPT_FORBID_REUSE,$value);
        return $this;
    }

    /**
     * TRUE to force the use of a new connection instead of a cached one.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableFreshConnect($value)
    {
        curl_setopt($this->_handle,CURLOPT_FRESH_CONNECT,$value);
        return $this;
    }

    /**
     * TRUE to use EPRT (and LPRT) when doing active FTP downloads.
     * Use FALSE to disable EPRT and LPRT and use PORT only.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableFtpUseEprt($value)
    {
        curl_setopt($this->_handle,CURLOPT_FTP_USE_EPRT,$value);
        return $this;
    }

    /**
     * TRUE to first try an EPSV command for FTP transfers before reverting
     * back to PASV. Set to FALSE to disable EPSV.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableFtpUseEpsv($value)
    {
        curl_setopt($this->_handle,CURLOPT_FTP_USE_EPSV,$value);
        return $this;
    }

    /**
     * TRUE to create missing directories when an FTP operation encounters a
     * path that currently doesn't exist.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableFtpCreateMissingDirs($value)
    {
        curl_setopt($this->_handle,CURLOPT_FTP_CREATE_MISSING_DIRS,$value);
        return $this;
    }

    /**
     * TRUE to append to the remote file instead of overwriting it.	.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableFtpAppend($value)
    {
        curl_setopt($this->_handle,CURLOPT_FTPAPPEND,$value);
        return $this;
    }

    /**
     * TRUE to append to the remote file instead of overwriting it.	.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableFtpAscii($value)
    {
        //todo:linkare a transferText
        return $this;
    }


    /**
     * TRUE to only list the names of an FTP directory.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableFtpListOnly($value)
    {
        curl_setopt($this->_handle,CURLOPT_FTPLISTONLY,$value);
        return $this;
    }

    /**
     * TRUE to include the header in the output.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableHeaderInOutput($value)
    {
        $this->_headersInOutput = true;
        curl_setopt($this->_handle,CURLOPT_HEADER,$value);
        return $this;
    }

    /**
     * TRUE to track the handle's request string.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableHeaderOut($value)
    {
        curl_setopt($this->_handle,CURLINFO_HEADER_OUT,$value);
        return $this;
    }

    /**
     * TRUE to reset the HTTP request method to GET.
     * Since GET is the default, this is only necessary if the request method has been changed
     *
     * @param $value
     * @return PhCURL
     */
    public function enableHttpGet($value)
    {
        $this->setMethod(self::METHOD_GET);
        return $this;
    }

    /**
     * TRUE to tunnel through a given HTTP proxy.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableHttpProxyTunnel($value)
    {
        curl_setopt($this->_handle,CURLOPT_HTTPPROXYTUNNEL,$value);
        return $this;
    }

    /**
     * Alias of enableReturnTransfer
     *
     * @param $value
     * @return PhCURL
     */
    public function enableMute($value)
    {
        $this->enableReturnTransfer($value);
        return $this;
    }

    /**
     * TRUE to scan the ~/.netrc file to find a username and password for the remote
     * site that a connection is being established with.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableNetRc($value)
    {
        curl_setopt($this->_handle,CURLOPT_NETRC,$value);
        return $this;
    }

    /**
     * TRUE to exclude the body from the output.
     * Request method is then set to HEAD.
     * Changing this to FALSE does not change it to GET.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableNobody($value)
    {
        curl_setopt($this->_handle,CURLOPT_NOBODY,$value);
        return $this;
    }

    /**
     * FALSE to disable the progress meter for PhCURL transfers.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableNoProgress($value)
    {
        curl_setopt($this->_handle,CURLOPT_NOPROGRESS,!($value));
        return $this;
    }

    /**
     * FALSE to ignore any PhCURL function that
     * causes a signal to be sent to the PHP process.
     * This is turned on by default in multi-threaded
     * SAPIs so timeout options can still be used.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableNoSignal($value)
    {
        curl_setopt($this->_handle,CURLOPT_NOSIGNAL,!($value));
        return $this;
    }

    /**
     *  Set application to do a regular HTTP POST.
     * This POST is the normal application/x-www-form-urlencoded kind,
     * most commonly used by HTML forms.
     *
     * @return PhCURL
     */
    public function enableHttpPost()
    {
        $this->setMethod(self::METHOD_POST);
        return $this;
    }

    /**
     * TRUE to HTTP PUT a file. The file to PUT must be set with
     * CURLOPT_INFILE and CURLOPT_INFILESIZ
     *
     * @param $value
     * @return PhCURL
     */
    public function enableHttpPut($value)
    {
        $this->setMethod(self::METHOD_PUT);
        return $this;
    }
    /**
     * TRUE to return the transfer as a string of the return value of
     * curl_exec() instead of outputting it out directly.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableReturnTransfer($value)
    {
        curl_setopt($this->_handle,CURLOPT_RETURNTRANSFER,$value);
        return $this;
    }

    /**
     * FALSE to stop PhCURL from verifying the peer's certificate.
     * Alternate certificates to verify against can be specified with the
     * CURLOPT_CAINFO option or a certificate directory can be specified
     * with the CURLOPT_CAPATH option.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableSslVerifier($value)
    {
        curl_setopt($this->_handle,CURLOPT_SSL_VERIFYPEER,$value);
        return $this;
    }
    /**
     * TRUE to use ASCII mode for FTP transfers.
     * For LDAP, it retrieves data in plain text instead of HTML.
     * On Windows systems, it will not set STDOUT to binary mode.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableTransferText($value)
    {
        curl_setopt($this->_handle,CURLOPT_TRANSFERTEXT,$value);
        return $this;
    }

    /**
     * TRUE to keep sending the username and password when following
     * locations (using CURLOPT_FOLLOWLOCATION), even when the hostname
     * has changed.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableUnrestrictedAuth($value)
    {
        curl_setopt($this->_handle,CURLOPT_UNRESTRICTED_AUTH,$value);
        return $this;
    }

    /**
     * TRUE to prepare for an upload.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableUpload($value)
    {
        curl_setopt($this->_handle,CURLOPT_UPLOAD,$value);
        return $this;
    }

    /**
     * TRUE to output verbose information.
     * Writes output to STDERR, or the file specified using CURLOPT_STDERR.
     *
     * @param $value
     * @return PhCURL
     */
    public function enableVerbose($value)
    {
        curl_setopt($this->_handle,CURLOPT_FTPAPPEND,$value);
        return $this;
    }

    /**
     * The size of the buffer to use for each read.
     * There is no guarantee this request will be fulfilled, however.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setBufferSize($value)
    {
        curl_setopt($this->_handle, CURLOPT_BUFFERSIZE, $value);
        return $this;
    }

    /**
     * Either CLOSEPOLICY_LEAST_RECENTLY_USED or CLOSEPOLICY_OLDEST.
     * There are three other CURLCLOSEPOLICY_ constants,
     * but PhCURL does not support them yet.
     *
     * @param $value
     * @return PhCURL
     * @throws Exception
     */
    public function setClosePolicy($value)
    {
        switch($value){
            case self::CLOSEPOLICY_LEAST_RECENTLY_USED:
            case self::CLOSEPOLICY_OLDEST:
                curl_setopt($this->_handle, CURLOPT_CLOSEPOLICY, $value);
                break;
            default:
                throw new Exception("Invalid closure policy specified");
                break;
        }
        return $this;
    }

    /**
     * The number of seconds to wait while trying to connect. Use 0 to wait indefinitely.
     *
     * @param mixed $seconds
     * @return PhCURL
     */
    public function setConnectTimeout($seconds)
    {
        curl_setopt($this->_handle,CURLOPT_CONNECTTIMEOUT,$seconds);
        return $this;
    }

    /**
     * The number of milliseconds to wait while trying to connect. Use 0 to wait indefinitely.
     * If libcurl is built to use the standard system name resolver,
     * that portion of the connect will still use full-second resolution
     * for timeouts with a minimum timeout allowed of one second.
     *
     * @param mixed $ms
     * @return PhCURL
     */
    public function setConnectTimeoutMs($ms)
    {
        curl_setopt($this->_handle,CURLOPT_CONNECTTIMEOUT_MS,$ms);
        return $this;
    }

    /**
     * The number of seconds to keep DNS entries in memory.
     * This option is set to 120 (2 minutes) by default.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setDnsCacheTimeout($value)
    {
        curl_setopt($this->_handle, CURLOPT_DNS_CACHE_TIMEOUT, $value);
        return $this;
    }
    /**
    * The FTP authentication method (when is activated): FTPAUTH_SSL (try SSL first),
    * FTPAUTH_TLS (try TLS first), or FTPAUTH_DEFAULT (let cURL decide).
    *
    * @param mixed $value
    * @return PhCURL
    */
    public function setFtpSslAuth($value)
    {
        switch ($value){
            case self::FTPAUTH_DEFAULT:
            case self::FTPAUTH_SSL:
            case self::FTPAUTH_TLS:
                curl_setopt($this->_handle, CURLOPT_FTPSSLAUTH, $value);
                break;
            default:
                throw new Exception("Invalid ftp auth method specified");
        }
        return $this;
    }

    /**
     * Sets which HTTP version to use
     *
     * @param mixed $version 1.1 for HTTP/1.1, 1.0 for HTTP/1.0
     * @return PhCURL
     */
    public function setHttpVersion($version = null)
    {
        if ($version == "1.0"){
            curl_setopt($this->_handle, CURLOPT_HTTP_VERSION,  CURL_HTTP_VERSION_1_0);
        } elseif($version == "1.1") {
            curl_setopt($this->_handle, CURLOPT_HTTP_VERSION,  CURL_HTTP_VERSION_1_1);
        } else{
            curl_setopt($this->_handle, CURL_HTTP_VERSION_NONE,  CURL_HTTP_VERSION_1_1);
        }
        return $this;
    }

    /**
     * The HTTP authentication method(s) to use. The options are:
     * AUTH_ANY for any suitable method, AUTH_ANYSAFE for
     * AUTH_DIGEST | AUTH_GSSNEGOTIATE  | AUTH_NTLM,
     * AUTH_BASIC
     *
     * @param mixed $value
     * @throws Exception
     * @return PhCURL
     */
    public function setHttpAuth($value)
    {
        switch ($value){
            case self::AUTH_ANY:
            case self::AUTH_ANYSAFE:
            case self::AUTH_BASIC:
            case self::AUTH_DIGEST:
            case self::AUTH_GSSNEG:
            case self::AUTH_NTLM:
                curl_setopt($this->_handle, CURLOPT_HTTPAUTH, $value);
                break;
            default:
                throw new Exception("No valid auth scheme defined");
                break;
        }
        return $this;
    }

    /**
     * The expected size, in bytes, of the file when uploading a file
     * to a remote site. Note that using this option will not stop
     * libcurl from sending more data, as exactly what is sent depends
     * on CURLOPT_READFUNCTION.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setInFileSize($value)
    {
        curl_setopt($this->_handle, CURLOPT_INFILESIZE, $value);
        return $this;
    }
    /**
     * The transfer speed, in bytes per second, that the transfer
     * should be below during the count of setLowSpeedTime()
     * seconds before PHP considers the transfer too slow and aborts.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setLowSpeedLimit($value)
    {
        curl_setopt($this->_handle, CURLOPT_LOW_SPEED_LIMIT, $value);
        return $this;
    }
    /**
     * Alias of setLowSpeedLimit
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setMinSpeedLimit($value)
    {
        return $this->setLowSpeedLimit($value);
    }
    /**
     * The number of seconds the transfer speed should be below setLowSpeedLimit()
     *  before PHP considers the transfer too slow and aborts.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setLowSpeedTime($value)
    {
        curl_setopt($this->_handle, CURLOPT_LOW_SPEED_TIME, $value);
        return $this;
    }
    /**
     * The maximum amount of persistent connections that are allowed. When the limit
     * is reached, setClosePolicy() is used to determine which connection to close.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setMaxConnects($value)
    {
        curl_setopt($this->_handle, CURLOPT_MAXCONNECTS, $value);
        return $this;
    }
    /**
     * The maximum amount of HTTP redirections to follow.
     * Use this option alongside enableFollowLocation.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setMaxRedirects($value)
    {
        curl_setopt($this->_handle, CURLOPT_MAXREDIRS, $value);
        return $this;
    }
    /**
     * Sets an alternative port number to connect to.
     *
     * @param mixed $port
     * @return PhCURL
     */
    public function setPort($port)
    {
        curl_setopt($this->_handle, CURLOPT_PORT, $port);
        return $this;
    }
    // @todo:implement CURLOPT_PROTOCOLS

    /**
     * The HTTP authentication method(s) to use for the proxy connection
     * Only AUTH_BASIC and AUTH_NTLM is supported for proxy auth.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setProxyAuth($value)
    {
        switch ($value){
            case self::AUTH_BASIC:
            case self::AUTH_NTLM:
                curl_setopt($this->_handle, CURLOPT_PROXYAUTH, $value);
                break;
            default:
                throw new Exception("No valid auth scheme defined");
                break;
        }
        return $this;
    }
    /**
     * The port number of the proxy to connect to
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setProxyPort($value)
    {
        curl_setopt($this->_handle, CURLOPT_PROXYPORT, $value);
        return $this;
    }

    /**
     * Choose proxy type
     * Possible values are: PROXY_SOCKS4,PROXY_SOCKS5,PROXY_HTTP
     *
     * @param mixed $value
     * @throws Exception
     * @return PhCURL
     */
    public function setProxyType($value)
    {
        switch ($value){
            case self::PROXY_SOCKS4:
            case self::PROXY_SOCKS5:
            case self::PROXY_HTTP:
                curl_setopt($this->_handle, CURLOPT_PROXYTYPE, $value);
                break;
            default:
                throw new Exception("Invalid proxy type");
        }

        return $this;
    }
    //todo: CURLOPT_REDIR_PROTOCOLS

    /**
     * The offset, in bytes, to resume a transfer from.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setResumeFrom($value)
    {
        curl_setopt($this->_handle, CURLOPT_RESUME_FROM, $value);
        return $this;
    }
    //todo:CURLOPT_SSL_VERIFYHOST

    /**
     * The SSL version (2 or 3) to use. By default PHP will try to
     * determine this itself, although in some cases this must be set manually.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setSslVersion($value)
    {
        curl_setopt($this->_handle, CURLOPT_SSLVERSION, $value);
        return $this;
    }

    /**
     * How CURLOPT_TIMEVALUE is treated. Use CURL_TIMECOND_IFMODSINCE to return
     * the page only if it has been modified since the time specified in
     * CURLOPT_TIMEVALUE (setTimeValue). If it hasn't been modified, a "304 Not Modified"
     * header will be returned assuming CURLOPT_HEADER is TRUE. Use
     * CURL_TIMECOND_IFUNMODSINCE for the reverse effect.
     * CURL_TIMECOND_IFMODSINCE is the default.
     *
     * @param mixed $condition
     * @throws Exception
     * @return PhCURL
     */
    public function setTimeCondition($condition)
    {
        switch($condition){
            case self::TIMECOND_IFMODSINCE:
            case self::TIMECOND_IFUNMODSINCE:
                curl_setopt($this->_handle, CURLOPT_TIMECONDITION , $condition);
                break;
            default:
                throw new Exception("Invalid Time Condition Specified");
                break;
        }
        return $this;
    }
    /**
     * The maximum number of seconds to allow PhCURL functions to execute.
     *
     * @param mixed $seconds
     * @return PhCURL
     */
    public function setTimeout($seconds)
    {
        curl_setopt($this->_handle, CURLOPT_TIMEOUT, $seconds);
        return $this;
    }
    /**
     * The maximum number of milliseconds to allow PhCURL functions to execute.
     * If libcurl is built to use the standard system name resolver, that portion
     * of the connect will still use full-second resolution for timeouts
     * with a minimum timeout allowed of one second.
     *
     * @param mixed $ms
     * @return PhCURL
     */
    public function setTimeoutMs($ms)
    {
        curl_setopt($this->_handle, CURLOPT_TIMEOUT_MS, $ms);
        return $this;
    }
    /**
     * The time in seconds since January 1st, 1970.
     * The time will be used by CURLOPT_TIMECONDITION (setTimeCondition)
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setTimeValue($value)
    {
        curl_setopt($this->_handle, CURLOPT_TIMEVALUE, $value);
        return $this;
    }
    /**
     * If a download exceeds this speed (counted in bytes per second) on cumulative
     * average during the transfer, the transfer will pause to keep the average rate
     * less than or equal to the parameter value. Defaults to unlimited speed.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setMaxRecvSpeedLarge($value)
    {
        curl_setopt($this->_handle, CURLOPT_MAX_RECV_SPEED_LARGE, $value);
        return $this;
    }
    /**
     * If an upload exceeds this speed (counted in bytes per second) on cumulative
     * average during the transfer, the transfer will pause to keep the average rate
     * less than or equal to the parameter value. Defaults to unlimited speed.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setMaxSendSpeedLarge($value)
    {
        curl_setopt($this->_handle, CURLOPT_MAX_SEND_SPEED_LARGE, $value);
        return $this;
    }

    /**
     * A bitmask consisting of one or more of SSH_AUTH_ types
     * Set to SSH_AUTH_ANY  to let libcurl pick one
     *
     * @param mixed $value
     * @throws Exception
     * @return PhCURL
     */
    public function setSshAuthTypes($value)
    {
        switch ($value){
            case self::SSH_AUTH_ANY:
            case self::SSH_AUTH_HOST:
            case self::SSH_AUTH_KEYBOARD:
            case self::SSH_AUTH_PASSWORD:
            case self::SSH_AUTH_PUBLICKEY:
                curl_setopt($this->_handle, CURLOPT_SSH_AUTH_TYPES, $value);
                break;
            default:
                throw new Exception("Invalid ssh auth specified");
                break;
        }

        return $this;
    }
    /*** String Values **/
    /**
     * The name of a file holding one or more certificates to verify the
     * peer with. This only makes sense when used in combination
     * with CURLOPT_SSL_VERIFYPEER
     *
     * @param mixed $value Absolute path of certificate
     * @return PhCURL
     */
    public function setCaInfo($value)
    {
        curl_setopt($this->_handle, CURLOPT_CAINFO, $value);
        return $this;
    }
    /**
     * A directory that holds multiple CA certificates.
     * Use this option alongside CURLOPT_SSL_VERIFYPEER.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setCaPath($value)
    {
        curl_setopt($this->_handle, CURLOPT_CAPATH, $value);
        return $this;
    }
    /**
     * The contents of the "Cookie: " header to be used in the HTTP request.
     * Note that multiple cookies are separated with a semicolon followed by
     * a space (e.g., "fruit=apple; colour=red")
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setCookieValue($value)
    {
        curl_setopt($this->_handle, CURLOPT_COOKIE, $value);
        return $this;
    }
    /**
     * The name of the file containing the cookie data. The cookie file can be
     * in Netscape format, or just plain HTTP-style headers dumped into a file.
     * If the name is an empty string, no cookies are loaded, but cookie handling
     * is still enabled.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setCookieFile($value)
    {
        curl_setopt($this->_handle, CURLOPT_COOKIEFILE, $value);
        return $this;
    }
    /**
     * The name of a file to save all internal cookies to when the handle
     * is closed, e.g. after a call to curl_close.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setCookieJar($value)
    {
        curl_setopt($this->_handle, CURLOPT_COOKIEJAR, $value);
        return $this;
    }
    /**
     * A custom request method to use instead of "GET" or "HEAD" when doing a HTTP request.
     * This is useful for doing "DELETE" or other, more obscure HTTP requests.
     * Valid values are things like "GET", "POST", "CONNECT" and so on; i.e.
     * Do not enter a whole HTTP request line here.
     * For instance, entering "GET /index.html HTTP/1.0\r\n\r\n" would be incorrect.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setCustomRequest($value)
    {
        curl_setopt($this->_handle, CURLOPT_CUSTOMREQUEST, $value);
        return $this;
    }
    //todo:CURLOPT_EGDSOCKET

    /**
     * The contents of the "Accept-Encoding: " header.
     * This enables decoding of the response.
     * Supported encodings are "identity", "deflate", and "gzip".
     * If an empty string, "", is set, a header containing all
     * supported encoding types is sent.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setEncoding($value)
    {
        curl_setopt($this->_handle, CURLOPT_ENCODING, $value);
        return $this;
    }
    /**
     * The value which will be used to get the IP address to use for the FTP "POST"
     * instruction. The "POST" instruction tells the remote server to connect
     * to our specified IP address. The string may be a plain IP address,
     * a hostname, a network interface name (under Unix),
     * or just a plain '-' to use the systems default IP address.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setFtpPort($value)
    {
        curl_setopt($this->_handle, CURLOPT_FTPPORT, $value);
        return $this;
    }
    /**
     * The name of the outgoing network interface to use.
     * This can be an interface name, an IP address or a host name.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setInterface($value)
    {
        curl_setopt($this->_handle, CURLOPT_INTERFACE, $value);
        return $this;
    }
    /**
     * The password required to use the CURLOPT_SSLKEY or
     * CURLOPT_SSH_PRIVATE_KEYFILE protectedkey.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setKeyPasswd($value)
    {
        curl_setopt($this->_handle, CURLOPT_KEYPASSWD, $value);
        return $this;
    }
    //todo: kerberous

    /**
     * The full data to post in a HTTP "POST" operation. To post a file, prepend a
     * filename with @ and use the full path.
     * The filetype can be explicitly specified by following the filename with the
     * type in the format ';type=mimetype'. This parameter can either be passed
     * as a urlencoded string like 'para1=val1&para2=val2&...' or as an array
     * with the field name as key and field data as value.
     * If value is an array, the Content-Type header will be set to multipart/form-data.
     * As of PHP 5.2.0, value must be an array if files are passed to this option with the @ prefix.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setPostFields($value)
    {
        curl_setopt($this->_handle, CURLOPT_POSTFIELDS, $value);
        return $this;
    }
    /**
     * The HTTP proxy to tunnel requests through.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setProxy($value)
    {
        curl_setopt($this->_handle, CURLOPT_PROXY, $value);
        return $this;
    }
    /**
     * A username and password formatted as "[username]:[password]"
     * to use for the connection to the proxy.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setProxyUserPwd($value)
    {
        curl_setopt($this->_handle, CURLOPT_PROXYUSERPWD, $value);
        return $this;
    }
    /**
     * A filename to be used to seed the random number generator for SSL.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setRandomFile($value)
    {
        curl_setopt($this->_handle, CURLOPT_RANDOM_FILE, $value);
        return $this;
    }
    /**
     * Range(s) of data to retrieve in the format "X-Y" where X or Y are optional.
     * HTTP transfers also support several intervals,
     * separated with commas in the format "X-Y,N-M".
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setRange($value)
    {
        curl_setopt($this->_handle, CURLOPT_RANGE, $value);
        return $this;
    }
    /**
     * The contents of the "Referer: " header to be used in a HTTP request.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setReferer($value)
    {
        curl_setopt($this->_handle, CURLOPT_REFERER, $value);
        return $this;
    }
    /**
     * A string containing 32 hexadecimal digits.
     * The string should be the MD5 checksum of the remote host's public key,
     * and libcurl will reject the connection to the host unless the md5sums match.
     * This option is only for SCP and SFTP transfers.
     *
     * Added in PhCURL 7.17.1.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setSshHostPublicKeyMd5($value)
    {
        curl_setopt($this->_handle, CURLOPT_SSH_HOST_PUBLIC_KEY_MD5, $value);
        return $this;
    }
    /**
    * The file name for your public key.
    * If not used, libcurl defaults to $HOME/.ssh/id_dsa.pub
    * if the HOME environment variable is set,
    * and just "id_dsa.pub" in the current directory if HOME is not set.
    *
    * @param mixed $value
    * @return PhCURL
    */
    public function setSshPublicKeyfile($value)
    {
        curl_setopt($this->_handle, CURLOPT_SSH_PUBLIC_KEYFILE, $value);
        return $this;
    }

    /**
     * The file name for your protectedkey.
     * If the file is password-protected, set the password with
     * CURLOPT_KEYPASSWD. (setKeyPasswd)
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setSshPrivateKeyfile($value)
    {
        curl_setopt($this->_handle, CURLOPT_SSH_PRIVATE_KEYFILE, $value);
        return $this;
    }
    /**
     * A list of ciphers to use for SSL. For example,
     * RC4-SHA and TLSv1 are valid cipher lists.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setSslCipherList($value)
    {
        curl_setopt($this->_handle, CURLOPT_SSL_CIPHER_LIST, $value);
        return $this;
    }
    /**
     * The name of a file containing a PEM formatted certificate.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setSslCert($value)
    {
        curl_setopt($this->_handle, CURLOPT_SSLCERT, $value);
        return $this;
    }
    /**
     * The password required to use the
     * CURLOPT_SSLCERT (setSslCert) certificate.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setSslCertPasswd($value)
    {
        curl_setopt($this->_handle, CURLOPT_SSLCERTPASSWD, $value);
        return $this;
    }

    /**
     * The format of the certificate.
     * Supported formats are "PEM" (default), "DER", and "ENG".
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setSslCertType($value)
    {
        switch ($value){
            case "PEM":
            case "DER":
            case "ENG":
                curl_setopt($this->_handle, CURLOPT_SSLCERTTYPE, $value);
                break;
            default:
                throw new Exception("Invalid keyfile (format not supported)");
                break;
        }
        return $this;
    }
    /**
     * The identifier for the crypto engine of the
     * protectedSSL key specified in CURLOPT_SSLKEY.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setSslEngine($value)
    {
        curl_setopt($this->_handle, CURLOPT_SSLENGINE, $value);
        return $this;
    }
    /**
     * The identifier for the crypto engine used
     * for asymmetric crypto operations.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setSslEngineDefault($value)
    {
        curl_setopt($this->_handle, CURLOPT_SSLENGINE_DEFAULT, $value);
        return $this;
    }
    /**
     * The name of a file containing a protected SSL key.
     *
     * Added in 7.16.1.
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setSslKey($value)
    {
        curl_setopt($this->_handle, CURLOPT_SSLKEY, $value);
        return $this;
    }
    //todo: kerberous

    public function setSslKeypasswd($value)
    {
        curl_setopt($this->_handle, CURLOPT_SSLKEYPASSWD, $value);
        return $this;
    }

    /**
     * The format of the certificate. Supported formats are "PEM" (default), "DER", and "ENG".
     *
     * @param mixed $value
     * @return PhCURL
     */
    public function setSslKeyType($value)
    {
        switch ($value){
            case "PEM":
            case "DER":
            case "ENG":
                curl_setopt($this->_handle, CURLOPT_SSLKEYTYPE, $value);
                break;
            default:
                throw new Exception("Invalid keyfile (format not supported)");
                break;
        }
        return $this;
    }
    /**
    * Sets method
    *
    * @param mixed $method valid values are METHOD_GET,METHOD_POST,METHOD_PUT
    * @return PhCURL
    */
    public function setMethod($method)
    {
        switch($method){
            case self::METHOD_GET:
                curl_setopt($this->_handle, CURLOPT_HTTPGET, true);
                break;
            case self::METHOD_PUT:
                curl_setopt($this->_handle, CURLOPT_PUT, true);
                break;
            case self::METHOD_POST:
                curl_setopt($this->_handle, CURLOPT_POST, true);
                break;
        }
        return $this;
    }
    /**
     * The URL to fetch. This can also be set during construction
     *
     * @param mixed $url
     * @return PhCURL
     */
    public function setUrl($url)
    {
        $this->_url = $url;
        return $this->setUrlWithoutUpdateOfVariable($url);
    }
    /**
    * Set's url without updating of _url variable.
    * Used internaly
    *
    * @param mixed $url
    * @return PhCURL
    */
    protected function setUrlWithoutUpdateOfVariable($url)
    {
        curl_setopt($this->_handle, CURLOPT_URL, $url);
        return $this;
    }
    /**
    * The contents of the "User-Agent: " header to be used in a HTTP request.
    *
    * @param mixed $value
    * @return PhCURL
    */
    public function setUserAgent($value = 'Mozilla/5.0 (Windows NT 6.0; WOW64; rv:24.0) Gecko/20100101 Firefox/24.0')
    {
        curl_setopt($this->_handle, CURLOPT_USERAGENT, $value);
        return $this;
    }
    /**
    * A username and password formatted as "[username]:[password]"
    * to use for the connection.
    *
    * @param mixed $value
    * @return PhCURL
    */
    public function setUserPwd($value)
    {
        curl_setopt($this->_handle, CURLOPT_USERPWD, $value);
        return $this;
    }

    /**
    * Returns last received HTTP CODE
    *
    * @return int
    */
    public function getHttpCode()
    {
        return curl_getinfo( $this->_handle, CURLINFO_HTTP_CODE );
    }
    /**
    * Returns last effective url
    *
    * @return string $url
    */
    public function getUrl()
    {
        return curl_getinfo( $this->_handle, CURLINFO_EFFECTIVE_URL  );
    }
    /**
    * Returns time of retrieved document.
    * If unknown returns -1
    *
    * @return int
    */
    public function getFileTime()
    {
        return curl_getinfo( $this->_handle, CURLINFO_FILETIME   );
    }
    /**
    * Total transaction time in seconds for last transfer
    *
    * @return int Code
    */
    public function getTotalTime()
    {
        return curl_getinfo( $this->_handle, CURLINFO_TOTAL_TIME  );
    }
    /**
    * Time in seconds until name resolving was complete
    *
    * @return int Code
    */
    public function getNameLookupTime()
    {
        return curl_getinfo( $this->_handle, CURLINFO_NAMELOOKUP_TIME   );
    }
    /**
    * Time in seconds it took to establish the connection
    *
    */
    public function getConnectTime()
    {
        return curl_getinfo( $this->_handle, CURLINFO_CONNECT_TIME );
    }
    /**
    * Time in seconds from start until just before file transfer begins
    *
    */
    public function getPretransferTime()
    {
        return curl_getinfo( $this->_handle, CURLINFO_PRETRANSFER_TIME );
    }
    /**
    * Time in seconds until the first byte is about to be transferred
    *
    */
    public function getStartTransferTime()
    {
        return curl_getinfo( $this->_handle, CURLINFO_STARTTRANSFER_TIME );
    }
    /**
    * Number of redirects
    *
    */
    public function getRedirectCount()
    {
        return curl_getinfo( $this->_handle, CURLINFO_REDIRECT_COUNT);
    }
    /**
    * Time in seconds of all redirection steps before final transaction was started
    *
    */
    public function getRedirectTime()
    {
        return curl_getinfo( $this->_handle, CURLINFO_REDIRECT_TIME);
    }
    /**
    * Total number of bytes uploaded
    *
    */
    public function getSizeUpload()
    {
        return curl_getinfo( $this->_handle, CURLINFO_SIZE_UPLOAD);
    }
    /**
    * Total number of bytes downloaded
    *
    */
    public function getSizeDownload()
    {
        return curl_getinfo( $this->_handle, CURLINFO_SIZE_DOWNLOAD);
    }
    /**
    * Average download speed
    *
    */
    public function getSpeedDownload()
    {
        return curl_getinfo( $this->_handle, CURLINFO_SPEED_DOWNLOAD);
    }
    /**
    * Average upload speed
    *
    */
    public function getSpeedUpload()
    {
        return curl_getinfo( $this->_handle, CURLINFO_SPEED_UPLOAD);
    }
    /**
    * Total size of all headers received
    *
    */
    public function getHeaderSize()
    {
        return curl_getinfo( $this->_handle, CURLINFO_HEADER_SIZE);
    }
    /**
    * The request string sent.
    * For this to work, call enableHeaderOut()
    *
    */
    public function getHeaderOut()
    {
        return curl_getinfo($this->_handle, CURLINFO_HEADER_OUT);
    }
    /**
    * Returns input headers as array
    *
    */
    public function getHeadersIn()
    {
        return $this->_inputHeaders;
    }
    /**
    * Total size of issued requests, currently only for HTTP requests
    *
    */
    public function getRequestSize()
    {
        return curl_getinfo($this->_handle, CURLINFO_REQUEST_SIZE);
    }
    /**
    * Result of SSL certification verification requested by setting enableSslVerifier()
    *
    */
    public function getVerifyResult()
    {
        return curl_getinfo($this->_handle, CURLINFO_SSL_VERIFYRESULT);
    }
    /**
    *  content-length of download, read from Content-Length: field
    *
    */
    public function getContentLengthDownload()
    {
        return curl_getinfo($this->_handle, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    }
    /**
    * Specified size of upload
    *
    */
    public function getContentLengthUpload()
    {
        return curl_getinfo($this->_handle, CURLINFO_CONTENT_LENGTH_UPLOAD);
    }
    /**
    * Content-Type: of the requested document,
    * NULL indicates server did not send valid Content-Type: header
    *
    */
    public function getContentType()
    {
        return curl_getinfo($this->_handle, CURLINFO_CONTENT_TYPE );
    }
    /**
    * Returns all info
    *
    * @return object
    */
    public function getInfo()
    {
        return (object) curl_getinfo( $this->_handle );
    }

    public function GET(){
        $this->setMethod(self::METHOD_GET);
        //check for params
        if (count($this->_params)){
            $url = $this->_url . http_build_query($this->_params);
            $this->setUrlWithoutUpdateOfVariable($url);
        }
        return $this->execute();
    }

    public function POST($dontAutoAddParams = false){
        $this->setMethod(self::METHOD_GET);
        //check for params
        if ($dontAutoAddParams && count($this->_params)){
            $this->setPostFields($this->_params);
        }
        return $this->execute();
    }



    public function getData()
    {
        return $this->_data;
    }
    /**
    * Clear all params
    *
    */
    public function clearParams()
    {
        $this->_params = array();
        return $this;
    }
    public function addParam($name,$value)
    {
        $this->_params[$name] = $value;
        return $this;
    }
    public function addParams($params)
    {
        if (count($params)){

        }
        return $this;
    }

    /**
    * Execute Request
    *
    * @return PhCURL
    */
    public function execute()
    {
        $output = curl_exec( $this->_handle );
        //do i need to split output?
        if ($this->_headersInOutput && preg_match('/(.*?)\r\n\r\n(.*?)$/s', $output, $regs)) {
            $this->_data = $regs[2];
            $this->_inputHeaders = $regs[1];
        } else {
            $this->_data = $output;
        }
        return $this;
    }

    public function loadCommonSettings()
    {
        $this->enableReturnTransfer(true)
             ->enableFollowLocation(true)
             ->setMaxRedirects(10)
             ->enableHeaderOut(true)
             ->enableHeaderInOutput(true);
    }

    public function close(){
        curl_close($this->_handle);
    }
}