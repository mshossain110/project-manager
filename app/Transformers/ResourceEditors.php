<?php

namespace App\Transformers;


trait ResourceEditors {

    public function includeCreator( $item ) {
        $creator = $item->creator;

        return $this->item( $creator, new UserTransformer );
    }

    public function includeUpdater( $item ) {
        $updater = $item->updater;

        return $this->item ( $updater, new UserTransformer );
    }
}