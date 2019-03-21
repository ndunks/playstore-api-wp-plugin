<?php
$data = $post->data;
?><table class="table table-sm table-bordered table-details">
    <tr><td>Developer</td>
        <td>
            <a href="https://play.google.com/store/apps/developer?id=<?= urlencode($data['developer_name']) ?>" rel="nofollow" target="_blank"><?= $data['developer_name'] ?></a>
        </td>
    </tr>
    <?php

        if(isset($data['upload_date']))
            printf('<tr><td>%s</td><td>%s</td></tr>', 'Published', $data['upload_date'] );

        if(isset($data['downloads']))
            printf('<tr><td>%s</td><td>%s</td></tr>', 'Downloaded', $data['downloads']);

        if(isset($data['operatingSystems']))
            printf('<tr><td>%s</td><td>%s</td></tr>', 'Android Version', $operatingSystems);
        
        if(isset($data['type']))
            printf('<tr><td>%s</td><td>%s</td></tr>', 'Type', $data['type'] );
        
        if(isset($data['category']))
            printf('<tr><td>%s</td><td>%s</td></tr>', 'Category', $data['category'] );
        
        if(isset($data['version']))
            printf('<tr><td>%s</td><td>%s</td></tr>', 'Version', $data['version'] );
        
        if(isset($data['file_size']))
            printf('<tr><td>%s</td><td>%s</td></tr>', 'Size', $data['file_size'] );
        
        if(isset($data['rating'])): ?>
    <tr>
        <td colspan="2">
            <div class="text-center">
                <div class="h3">Rating <?php echo number_format($data['rating']['star_rating'],2) ?></div>
                <div class="star-container">
                    <div class="star">
                        <div class="star-fill" style="width: <?php echo $data['rating_percent'] ?>%;"></div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <?php endif; ?>
</table>