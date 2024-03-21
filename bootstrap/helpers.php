<?php

use App\Support\Arr;
use Illuminate\Console\Command;
use Illuminate\Console\OutputStyle;
use ZanySoft\Zip\Zip;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Str;
use Nwidart\Modules\Laravel\Module;

/**
 * 查看禁用函数
 */
if (!function_exists('disable_functions')) {
    function disable_functions($func = '')
    {
        $return = explode(',', ini_get('disable_functions'));
        if (empty($func)) return $return;
        return in_array($func, $return);
    }
}

/**
 * Markdown 转 HTML
 */
if (!function_exists('markdonw_to_html')) {
    function markdown_to_html($mark)
    {
        return Str::markdown($html);
        return Parsedown::instance()->text($mark);
    }
}
/**
 * HTML 转 Markdown
 */
if (!function_exists('html_to_markdown')) {
    function html_to_markdown($html)
    {
        $converter = new \League\HTMLToMarkdown\HtmlConverter(['strip_tags' => true]);
        return $converter->convert($html);
    }
}
/**
 * 爬虫获取网页基本信息
 */
if (!function_exists('get_site_info')) {
    function get_site_info($url)
    {
        $return = [
            'url' => $url,
            'raw' => null,
            'title' => null,
            'metas' => null,
            'icons' => null,
        ];
        $html = phpspider\core\requests::get($url);
        $return['raw'] = $html;
        $return['title'] = phpspider\core\selector::select($html, '//head//title');
        $metas = phpspider\core\selector::select($html, '//head//meta');
        if (!empty($metas)) {
            $return['metas'] = [];
            for ($i = 0; $i < count((array)$metas) + 1; $i++) {
                $name = phpspider\core\selector::select($html, "//head//meta[$i]/@name");
                $content = phpspider\core\selector::select($html, "//head//meta[$i]/@content");
                $return['metas'][$name] = $content;
            }
        }
        $return['icons'] = phpspider\core\selector::select($html, "//head//link[contains(@rel,'icon')]/@href");
        return $return;
    }
}
/**
 * 过滤，数据处理
 */
if (!function_exists('filter')) {
    function filter($data, $filters)
    {
    }
}


/**
 * 存储视图变量
 */
if (!function_exists('variable')) {
    function variable($view = null, $data = [], $mergeData = [])
    {
        Storage::put('variables/' . pathinfo($view)['basename'] . '.json', json_encode(array_merge($data, $mergeData), JSON_UNESCAPED_UNICODE));
    }
}
/**
 * 检测是否是 artisan 命令行
 */
if (!function_exists('is_artisan_cli')) {
    function is_artisan_cli()
    {
        return request()->server('PHP_SELF') === 'artisan';
    }
}
if (!function_exists('artisan_dump')) {
    function artisan_dump(...$values)
    {
        if (is_artisan_cli()) {
            foreach ($values as $value) {
                dump($value);
            }
        }
    }
}
if (!function_exists('artisan_echo')) {
    function artisan_echo($message, $func = null)
    {
        if (!empty($func)) $message = "[$func] $message";
        if (is_artisan_cli()) {
            dump("[" . date('Y-m-d H:i:s') . "] " . $message);
        }
    }
}
if (!function_exists('artisan_info')) {
    function artisan_info($message)
    {
        if (is_artisan_cli()) {
        }
    }
}

/**
 * 字符串前拼接
 */
if (!function_exists('str_json_prefix')) {
    function str_join_prefix($str1, $str2)
    {
        return $str2 . $str1;
    }
}
/**
 * 字符串后拼接
 */
if (!function_exists('str_json_suffix')) {
    function str_join_suffix($str1, $str2)
    {
        return $str2 . $str1;
    }
}

/**
 *
 */
if (!function_exists('to_array')) {
    function to_array($value)
    {
        return (array)$value;
    }
}
if (!function_exists('is_url')) {
    function is_url($string)
    {
        return filter_var($string, FILTER_VALIDATE_URL) !== false;
    }
}


if (!function_exists('getResHeaderValue')) {
    function getResHeaderValue($key, $responseHead)
    {
        $value = '';
        $headArr = explode("\r\n", $responseHead);

        foreach ($headArr as $loop) {

            if ($key == 'Http-Code') {

                if (preg_match('/HTTP\/1.[0-9]{1}([0-9]{3})/', $loop, $matches)) {

                    return $matches['1'];
                }
            } else {

                if (strpos($loop, $key) !== false) {

                    $value = trim(str_replace($key . ':', '', $loop));
                }
            }
        }

        return $value;
    }
}

if (!function_exists('curl_download')) {

    function curl_download($url, $range, $header = [])
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_ACCEPT_ENCODING, 'gzip');
        //设置关闭SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //设置分片
        curl_setopt($ch, CURLOPT_RANGE, $range);
        //设置header
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        //执行请求
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            thrownewException('下载文件异常:' . curl_error($ch));
        }
        //提取response_header和response_body

        $headSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $httpHeader = substr($response, 0, $headSize);
        if (!$httpHeader) {
            thrownewException('下载文件异常:未获取到响应头');
        }

        $fileStream = substr($response, $headSize);

        //解析header

        $length = getResHeaderValue('Content-Length', $httpHeader);

        $httpCode = getResHeaderValue('Http-Code', $httpHeader);

        curl_close($ch);

        //返回

        return [
            'code' => $httpCode,
            'length' => $length,
            'stream' => $fileStream,
        ];
    }
}

if (!function_exists('curl_headers')) {
    function curl_headers($url)
    {
        $return = get_headers($url, true);
        // 文件字节大小
        if (!empty($return['Content-Length'])) {
            $return['file_size'] = (int)(is_array($return['Content-Length']) ? end($return['Content-Length']) : $return['Content-Length']);
        }
        // 文件md5
        if (!empty($return['ETag'])) {
            $return['file_md5'] = str_replace("\"", "", $return['ETag']);
        }
        return $return;
    }
}

if (!function_exists('get_module_path')) {
    function get_module_path()
    {
    }
}
if (!function_exists('get_zip_extension')) {
    function get_zip_extension($path)
    {
        $types = ['7z', 'rar', 'zip', 'tar', 'tar.gz', 'tgz'];
        foreach ($types as $type) {
            if (substr($path, -strlen($type)) === $type) {
                return $type;
                break;
            }
        }
        return false;
    }
}
if (!function_exists('unzip')) {
    function unzip($from, $to)
    {
        $ext = get_zip_extension($from);
        if (in_array($ext, ['tar.gz',])) {
            $zip = new \PharData($from);
            //解压后的路径 数组或者字符串指定解压解压的文件，null为全部解压  是否覆盖
            $zip->extractTo($to, null, true);
        } elseif (in_array($ext, ['rar'])) {
            // var_dump("rar");
            $rar_file = rar_open($from);
            if ($rar_file === false) {
                exit("Failed to open RAR file.");
            }
            $entries = rar_list($rar_file);
            foreach ($entries as $entry) {
                $entry->extract($to);
            }
            rar_close($rar_file);
        } else {
            Zip::open($from)->extract($to, true);
        }
    }
}
if (!function_exists('get_original_data')) {
    function get_original_data()
    {
    }
}

if (!function_exists('module_view')) {
    function module_view($view = null, $data = [], $mergeData = [])
    {
        return view($view, $data, $mergeData);
    }
}
if (!function_exists('module_current')) {
    function module_current($view = null, $data = [], $mergeData = [])
    {
        return view($view, $data, $mergeData);
    }
}
if (!function_exists('module_config')) {
    function module_config($view = null, $data = [], $mergeData = [])
    {
        return view($view, $data, $mergeData);
    }
}


if (!function_exists('get_user_os')) {
    function get_user_os()
    {

        $agent = $_SERVER['HTTP_USER_AGENT'];

        if (strpos($agent, "NT 6.1")) {

            $os_name = "Windows 7";
        } elseif (strpos($agent, "NT 5.1")) {

            $os_name = "Windows XP (SP2)";
        } elseif (strpos($agent, "NT 5.2") && strpos($agent, "WOW64")) {

            $os_name = "Windows XP 64-bit Edition";
        } elseif (strpos($agent, "NT 5.2")) {

            $os_name = "Windows 2003";
        } elseif (strpos($agent, "NT 6.0")) {

            $os_name = "Windows Vista";
        } elseif (strpos($agent, "NT 5.0")) {

            $os_name = "Windows 2000";
        } elseif (strpos($agent, "4.9")) {

            $os_name = "Windows ME";
        } elseif (strpos($agent, "NT 4")) {

            $os_name = "Windows NT 4.0";
        } elseif (strpos($agent, "98")) {

            $os_name = "Windows 98";
        } elseif (strpos($agent, "95")) {

            $os_name = "Windows 95";
        } elseif (strpos($agent, "Linux")) {

            $os_name = "Linux";
        }

        if (strpos($os_name, "Linux") !== false) {

            $os_str = "Linux操作系统";
        } else if (strpos($os_name, "Windows") !== false) {

            $os_str = "Windows操作系统";
        } else {

            $os_str = "未知操作系统";
        }

        return $os_str;
    }
}
if (!function_exists('is_win')) {
    function is_win()
    {
        return strpos(PHP_OS, 'WIN') !== false;
    }
}
if (!function_exists('is_linux')) {
    function is_linux()
    {
        return strpos(PHP_OS, 'Linux') !== false;
    }
}


if (!function_exists('decode')) {
    // 解码
    function decode($data, $option = [])
    {
    }
}
if (!function_exists('encode')) {
    // 编码
    function encode()
    {
    }
}
