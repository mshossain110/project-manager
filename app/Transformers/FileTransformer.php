<?php

namespace App\Transformers;

use App\File;
use League\Fractal\TransformerAbstract;
use File_System;


class FileTransformer extends TransformerAbstract {

    use ResourceEditors;

    protected $defaultIncludes = [
        'creator', 'updater'
    ];

    public function transform( File $item ) {
        $file = File_System::get_file( $item->attachment_id );
        $file = is_array( $file ) ? $file : [];
        
        $model_data = [
            'id'            => (int) $item->id,
            'fileable_id'   => $item->fileable_id,
            'fileable_type' => $item->fileable_type,
            'directory'     => $item->directory,
            'attachment_id' => $item->attachment_id,
            'attached_at'   => format_date( $item->created_at ),
            'fileable'      => $this->get_fileabel($item)
        ];

        return array_merge( $model_data, $file );
    }


    public function get_fileabel( $item ) {

        if ( $item->fileable_type == 'comment') {
            $result = $item->comment()->get()->first();
            return $result->getAttributes(); 
        }
    }
}