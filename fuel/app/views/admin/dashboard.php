
<div>
    <h2>Sales this month <?php echo $month ?></h2>
    <table class="summary">
        <tr>
            <th>Category</th>
            <th>Items</th>
            <th>Revenue</th>
            <th>Profit</th>
        </tr>
        <tr>
            <td>Users</td>
            <td><?php echo $useritems ?></td>
            <td><?php echo $usertotal ?> €</td>
            <td><?php echo $userprofit ?> €</td>
        </tr>
        <tr>
            <td>Events</td>
            <td><?php echo $eventitems ?></td>
            <td><?php echo $eventtotal ?></td>
            <td><?php echo $eventprofit ?> €</td>
        </tr>
        <tr class="totall">
            <td>Total</td>
            <td><?php echo ($eventitems + $useritems) ?></td>
            <td><?php echo ($eventtotal + $usertotal) ?> €</td>
            <td><?php echo ($eventprofit + $userprofit) ?> €</td>
        </tr>
    </table>
    
</div>
