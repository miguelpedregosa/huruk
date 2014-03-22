<?php
/**
 *
 * User: migue
 * Date: 9/02/14
 * Time: 21:02
 */

namespace Huruk\Assets;

use Assetic\Asset\AssetInterface;
use Assetic\Asset\AssetCollection;

class Utils
{
    /**
     * Convierte el path de un Asset en un cadena de texto que puede usarse para referirse a dicho asset
     * @param AssetInterface $asset
     * @param null $collection_name
     * @param null $collection_extension
     * @return string
     */
    public static function getName(AssetInterface $asset, $collection_name = null, $collection_extension = null)
    {
        if ($asset instanceof AssetCollection) {
            $source_path = '';
            foreach ($asset as $as) {
                if ($as instanceof AssetInterface) {
                    $source_path .= $as->getSourcePath();
                }
            }
            $name = (is_null($collection_name)) ? 'asset_collection' : $collection_name;
            $extension = (is_null($collection_extension)) ? '' : '_' . $collection_extension;

            return md5($name . '_' . $source_path . $extension);

        } else {
            return md5($asset->getSourcePath());
        }

    }

    /**
     * Devuelve el nombre con el que debe escribirse un asset compilado
     * @param AssetInterface $asset
     * @param null $collection_name
     * @param null $collection_extension
     * @return null|string
     */
    public static function getTargetFileName(
        AssetInterface $asset,
        $collection_name = null,
        $collection_extension = null
    ) {
        if ($asset instanceof AssetCollection) {
            return self::getAssetFileCollectionTargetFileName(
                $asset,
                $collection_name,
                $collection_extension,
                $collection_extension
            );
        } else {
            return self::getAssetFileTargeFileName($asset);
        }
    }

    /**
     * @param AssetCollection $collection
     * @param null $collection_name
     * @param null $collection_extension
     * @return string
     */
    private static function getAssetFileCollectionTargetFileName(
        AssetCollection $collection,
        $collection_name = null,
        $collection_extension = null
    ) {
        $last_mod = $collection->getLastModified();
        $filters = $collection->getFilters();
        $filters_str = md5(serialize($filters));
        $source_path = '';
        foreach ($collection as $asset) {
            if ($asset instanceof AssetInterface) {
                $source_path .= $asset->getSourcePath();
            }
        }

        $name = (is_null($collection_name)) ? 'asset_collection' : $collection_name;
        $extension = (is_null($collection_extension)) ? '' : '.' . $collection_extension;

        return $name . '_' . md5($source_path) . '_' . $filters_str . '_' . $last_mod . $extension;
    }


    /**
     * Devuelve el target name para un objeto de tipo AssetInterface
     * @param \Assetic\Asset\AssetInterface $asset
     * @return null|string
     */
    private static function getAssetFileTargeFileName(AssetInterface $asset)
    {
        $last_mod = $asset->getLastModified();
        $filters = $asset->getFilters();
        $filters_str = md5(serialize($filters));
        $path_info = pathinfo($asset->getSourcePath());
        $target_name =
            $path_info['dirname'] .
            '/' . $path_info['filename'] . '_' . $filters_str . '_' . $last_mod . '.' . $path_info['extension'];
        $target_name = str_replace('./', '', $target_name);

        return $target_name;
    }
}
 