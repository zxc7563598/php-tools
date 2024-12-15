<?php

namespace Hejunjie\Tools;

/**
 * 图片处理类
 * @package Hejunjie\Tools
 */
class Img
{
    /**
     * 从 URL 下载图片
     * 
     * @param string $url URL地址
     * @param string $saveDir 存储文件夹
     * @param string $fileName 图片名称（不指定则随机）
     * 
     * @return null|string 完整图片路径
     * @throws Exception 
     */
    public static function downloadImageFromUrl(string $url, string $saveDir, string $fileName = ''): ?string
    {
        // 校验URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception('无效的URL地址');
        }
        // 检查并创建目录
        if (!is_dir($saveDir) && !mkdir($saveDir, 0755, true) && !is_dir($saveDir)) {
            throw new \Exception('创建目录失败: ' . $saveDir);
        }
        try {
            $imageData = file_get_contents($url);
            if ($imageData === false) {
                throw new \Exception('从URL下载图片失败');
            }
            // 获取图片后缀
            $imageInfo = getimagesizefromstring($imageData);
            if ($imageInfo === false) {
                throw new \Exception('从URL获取的图片数据无效');
            }
            $extension = image_type_to_extension($imageInfo[2], false); // 获取图片的扩展名
            // 如果没有传入文件名称，生成随机名称
            if (empty($fileName)) {
                $fileName = uniqid('image_', true) . '.' . $extension;
            } else {
                $fileName .= '.' . $extension;  // 添加扩展名
            }
            $savePath = rtrim($saveDir, '/') . '/' . $fileName;
            // 保存图片
            if (file_put_contents($savePath, $imageData) === false) {
                throw new \Exception('保存图片失败: ' . $savePath);
            }
            return $savePath; // 返回完整保存路径
        } catch (\Exception $e) {
            throw new \Exception('下载图片时出错: ' . $e->getMessage());
        }
    }

    /**
     * 将图片转换为 Base64 字符串
     * 
     * @param string $imagePath 图片路径
     * 
     * @return null|string 图片 Base64
     * @throws Exception 
     */
    public static function imageToBase64(string $imagePath): ?string
    {
        // 检查文件是否存在
        if (!file_exists($imagePath)) {
            throw new \Exception('文件不存在: ' . $imagePath);
        }
        try {
            // 获取文件内容
            $imageData = file_get_contents($imagePath);
            if ($imageData === false) {
                throw new \Exception('读取文件失败: ' . $imagePath);
            }
            // 获取图片信息
            $imageInfo = getimagesize($imagePath);
            if ($imageInfo === false) {
                throw new \Exception('获取图片信息失败: ' . $imagePath);
            }
            // 将图片编码为 Base64
            $base64 = base64_encode($imageData);
            $mimeType = $imageInfo['mime'];
            return "data:$mimeType;base64,$base64";
        } catch (\Exception $e) {
            throw new \Exception('转换图片为 Base64 时出错: ' . $e->getMessage());
        }
    }

    /**
     * 将 Base64 字符串保存为图片
     * 
     * @param string $base64String 图片 Base64
     * @param string $saveDir 存储文件夹
     * @param string $fileName 图片名称（不指定则随机）
     * 
     * @return null|string 完整图片路径
     * @throws Exception 
     */
    public static function base64ToImage(string $base64String, string $saveDir, string $fileName = ''): ?string
    {
        // 检查 Base64 数据格式是否正确
        $imageData = explode(',', $base64String);
        if (count($imageData) !== 2) {
            throw new \Exception('Base64 数据无效');
        }
        // 提取并验证 MIME 类型和扩展名
        if (preg_match('/^data:image\/([a-zA-Z0-9]+);base64$/', $imageData[0], $matches)) {
            $extension = $matches[1]; // 正确获取扩展名
        } else {
            throw new \Exception('无效的 Base64 数据前缀');
        }
        // 解码 Base64 数据
        $decodedData = base64_decode($imageData[1]);
        if ($decodedData === false) {
            throw new \Exception('Base64 数据解码失败');
        }
        // 如果没有传入文件名称，生成随机名称
        if (empty($fileName)) {
            $fileName = uniqid('image_', true) . '.' . $extension;
        } else {
            $fileName .= '.' . $extension;
        }
        // 检查并创建目录
        if (!is_dir($saveDir) && !mkdir($saveDir, 0755, true) && !is_dir($saveDir)) {
            throw new \Exception('创建目录失败: ' . $saveDir);
        }
        $savePath = rtrim($saveDir, '/') . '/' . $fileName;
        // 保存图片
        if (file_put_contents($savePath, $decodedData) === false) {
            throw new \Exception('保存图片失败: ' . $savePath);
        }
        // 设置文件权限
        chmod($savePath, 0755);
        // 返回完整保存路径
        return $savePath;
    }


    /**
     * 压缩图片到指定大小（单位 KB），支持多种格式转换为 JPEG
     * 
     * @param string $imagePath 原图片路径
     * @param string $saveDir 存储文件夹
     * @param int $targetSizeKB 指定压缩大小
     * @param string $fileName 存储名称
     * 
     * @return null|string 完整图片路径
     * @throws Exception 
     */
    public static function compressImage(string $imagePath, string $saveDir, int $targetSizeKB, string $fileName = ''): ?string
    {
        // 检查图片是否存在
        if (!file_exists($imagePath)) {
            throw new \Exception('图片不存在: ' . $imagePath);
        }
        // 获取图片信息
        $imageInfo = getimagesize($imagePath);
        if ($imageInfo === false) {
            throw new \Exception('获取图片信息失败: ' . $imagePath);
        }
        $mimeType = $imageInfo['mime'];
        // 根据不同的 MIME 类型创建图像资源
        switch ($mimeType) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($imagePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($imagePath);
                break;
            case 'image/bmp':
                $image = imagecreatefrombmp($imagePath);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($imagePath);
                break;
            default:
                throw new \Exception('不支持的图片格式: ' . $mimeType);
        }
        // 如果没有传入文件名称，生成随机名称
        if (empty($fileName)) {
            $fileName = uniqid('compressed_', true) . '.jpg';
        } else {
            $fileName .= '.jpg';  // 添加 .jpg 扩展名
        }
        $savePath = rtrim($saveDir, '/') . '/' . $fileName;
        // 检查并创建目录
        if (!is_dir($saveDir) && !mkdir($saveDir, 0755, true) && !is_dir($saveDir)) {
            throw new \Exception('创建目录失败: ' . $saveDir);
        }
        // 初始化压缩质量
        $quality = 90;
        $step = 5;  // 每次降低 5 的质量点
        $compressed = false;
        try {
            // 不断降低质量，直到文件大小小于目标大小
            while ($quality > 10) {
                imagejpeg($image, $savePath, $quality);
                $fileSizeKB = filesize($savePath) / 1024;
                if ($fileSizeKB <= $targetSizeKB) {
                    $compressed = true;
                    break;
                }
                $quality -= $step;
            }
            if (!$compressed) {
                throw new \Exception('无法将图片压缩到指定大小: ' . $targetSizeKB . 'KB');
            }
        } finally {
            // 销毁图像资源
            imagedestroy($image);
        }
        return $savePath;
    }

    /**
     * 调整图片分辨率，保持宽高比
     * 
     * @param string $imagePath 原图片路径
     * @param string $saveDir 存储文件夹
     * @param int $maxWidth 图片最大宽度，0为不限制
     * @param int $maxHeight 图片最大高度，0为不限制
     * @param string $fileName 存储名称
     * 
     * @return null|string 完整图片路径
     * @throws Exception 
     */
    public static function resizeImage(string $imagePath, string $saveDir, int $maxWidth, int $maxHeight, string $fileName = ''): ?string
    {
        // 检查图片是否存在
        if (!file_exists($imagePath)) {
            throw new \Exception('图片不存在: ' . $imagePath);
        }
        // 获取图片信息
        $imageInfo = getimagesize($imagePath);
        if ($imageInfo === false) {
            throw new \Exception('无法获取图片信息: ' . $imagePath);
        }
        $mimeType = $imageInfo['mime'];
        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        // 根据 MIME 类型创建图像资源
        switch ($mimeType) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($imagePath);
                $extension = 'jpg';
                break;
            case 'image/png':
                $image = imagecreatefrompng($imagePath);
                $extension = 'png';
                break;
            case 'image/gif':
                $image = imagecreatefromgif($imagePath);
                $extension = 'gif';
                break;
            case 'image/bmp':
                $image = imagecreatefrombmp($imagePath);
                $extension = 'bmp';
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($imagePath);
                $extension = 'webp';
                break;
            default:
                throw new \Exception('不支持的图片格式: ' . $mimeType);
        }
        // 计算新的宽高，保持宽高比
        $ratio = $originalWidth / $originalHeight;
        if ($maxWidth > 0 && $maxHeight === 0) {
            $newWidth = min($maxWidth, $originalWidth);
            $newHeight = $newWidth / $ratio;
        } elseif ($maxHeight > 0 && $maxWidth === 0) {
            $newHeight = min($maxHeight, $originalHeight);
            $newWidth = $newHeight * $ratio;
        } else {
            $newWidth = min($maxWidth, $originalWidth);
            $newHeight = min($maxHeight, $originalHeight);
            if ($newWidth / $newHeight > $ratio) {
                $newWidth = $newHeight * $ratio;
            } else {
                $newHeight = $newWidth / $ratio;
            }
        }
        // 创建新的图像资源
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        // 处理透明背景（针对 PNG 和 GIF）
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
            imagefill($resizedImage, 0, 0, $transparent);
        }
        // 执行图像拷贝并调整大小
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
        // 如果没有传入文件名称，生成随机名称
        if (empty($fileName)) {
            $fileName = uniqid('resized_', true) . '.' . $extension;
        } else {
            $fileName .= '.' . $extension;
        }
        $savePath = rtrim($saveDir, '/') . '/' . $fileName;
        // 检查并创建目录
        if (!is_dir($saveDir) && !mkdir($saveDir, 0755, true) && !is_dir($saveDir)) {
            throw new \Exception('创建目录失败: ' . $saveDir);
        }
        // 保存图像
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($resizedImage, $savePath, 90);
                break;
            case 'png':
                imagepng($resizedImage, $savePath);
                break;
            case 'gif':
                imagegif($resizedImage, $savePath);
                break;
            case 'bmp':
                imagebmp($resizedImage, $savePath);
                break;
            case 'webp':
                imagewebp($resizedImage, $savePath);
                break;
        }
        // 销毁图像资源
        imagedestroy($image);
        imagedestroy($resizedImage);
        return $savePath;
    }
}
