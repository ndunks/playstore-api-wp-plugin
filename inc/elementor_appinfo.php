<?php
$data = $post->data;

?><table class="table table-sm table-bordered table-details">
    <tr><td>Developer</td>
        <td>
            <a href="https://play.google.com/store/apps/developer?id=[apk :urlencode developer_name]" rel="nofollow" target="_blank">[apk developer_name]</a>
        </td>
    </tr>
    <?php

        if(isset($data['upload_date']))
            printf('<tr><td>%s</td><td>%s</td></tr>', 'Published', '[apk upload_date]' );

        if(isset($data['downloads']))
            printf('<tr><td>%s</td><td>%s</td></tr>', 'Downloaded', '[apk downloads]');

        if(isset($data['operatingSystems']))
            printf('<tr><td>%s</td><td>%s</td></tr>', 'Android Version', '[apk operatingSystems]');
        
        if(isset($data['type']))
            printf('<tr><td>%s</td><td>%s</td></tr>', 'Type', '[apk type]' );
        
        if(isset($data['category']))
            printf('<tr><td>%s</td><td>%s</td></tr>', 'Category', '[apk category]' );
        
        if(isset($data['version']))
            printf('<tr><td>%s</td><td>%s</td></tr>', 'Version', '[apk version]' );
        
        if(isset($data['file_size']))
            printf('<tr><td>%s</td><td>%s</td></tr>', 'Size', '[apk file_size]' );
        
        if(isset($data['rating'])): ?>
    <tr>
        <td colspan="2">
            <div class="text-center">
                <div class="h3">Rating [apk :number_format(*,2) rating star_rating]</div>
                <div class="star-container">
                    <div class="star">
                        <div class="star-fill" style="width: [apk rating_percent]%;"></div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <?php endif; ?>
</table>