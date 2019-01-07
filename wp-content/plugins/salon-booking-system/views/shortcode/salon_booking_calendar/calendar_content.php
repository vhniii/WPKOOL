<?php
/**
 * @var array $data
 */
?>

<table class="table sln-salon-booking-calendar" data-attendantsIds="<?php echo $data['attendantsIds']; ?>" data-visibility="<?php echo $data['visibility']; ?>" data-showDays="<?php echo $data['showDays']; ?>">
    <thead>
    <tr>
        <td class="sln-sc--cal__date"><?php _e('Date','salon-booking-system'); ?></td>
        <?php foreach($data['attendants'] as $att): ?>
        <td><?php echo $att['name'] ?></td>
        <?php endforeach ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach($data['dates'] as $k => $date): ?>
        <tr>
            <td data-th="<?php _e('Date','salon-booking-system'); ?>" class="sln-sc--cal__date"><?php echo $date ?></td>
            <?php foreach($data['attendants'] as $att): ?>
                <td data-th="<?php echo $att['name'];?>" class="sln-sc--cal__attendant">
                    <?php if(isset($att['events'][$k])): ?>
                        <?php foreach($att['events'][$k] as $event): ?>
                            <div style="text-transform: none;"><span data-toggle="tooltip" data-placement="right" data-html="true" title="<?php echo $event['desc'] ?>"><?php echo $event['title'] ?></span></div>
                        <?php endforeach ?>
                    <?php endif ?>
                </td>
            <?php endforeach ?>
        </tr>
    <?php endforeach ?>
    <?php ?>
    </tbody>
</table>