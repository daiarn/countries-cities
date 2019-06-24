<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>css/main.css">
    <title>Cities</title>
</head>
<body>

<div class="container">
    <form action="<?php echo URL; ?>city/search/<?php echo $data['country'] ?>" method="get">
        <input type="text" name="query" placeholder="Enter something to search"
               value="<?php echo $_SESSION['query'] ?>"/>
        <input type="submit" value="Search"/>
    </form>
</div>

<div class="container">
    <div class="centerText">
        <h2>Table of cities</h2>
    </div>
    <a href="<?php echo URL ?>" class="button">Back to Countries</a>
    <a href="<?php echo URL ?>city/clear/<?php echo $data['country'] ?>" class="button">Clear search and filter</a>
    <br/><br/>
    <form action="<?php echo URL ?>city/filter/<?php echo $data['country'] ?>" method="get">
        <div class="centerText">
            <h3>Filter by date</h3>
        </div>
        <input type="hidden" name="pageno" value="<?php echo $_SESSION['pageno'] ?>">
        <label for="from">From</label>
        <input type="date" name="from" id="from" value="<?php echo $_SESSION['from'] ?>"/>
        <label for="to">To</label>
        <input type="date" name="to" id="to" value="<?php echo $_SESSION['to'] ?>"/>
        <input type="submit" class="button buttonSuccess" name="filter" value="Filter"/>
    </form>
    <form action="<?php echo URL ?>city/index/<?php echo $_SESSION['country'] ?>/<?php echo "?pageno=" . $_SESSION['pageno'] ?>"
          method="get">
        <input type="hidden" name="pageno" value="<?php echo $_SESSION['pageno'] ?>">
        <button type="submit" class="button buttonSuccess" name="sort">Sort</button>
    </form>
    <table>
        <tr>
            <th>Name</th>
            <th>Area</th>
            <th>People Count</th>
            <th>Post code</th>
            <th>Action</th>
        </tr>
        <?php if (isset($data['cities'])) {
            while ($row = $data['cities']->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['name'] ?></td>
                    <td><?php echo $row['area'] ?></td>
                    <td><?php echo $row['people_count'] ?></td>
                    <td><?php echo $row['post_code'] ?></td>
                    <td>
                        <a href="<?php echo URL; ?>city/update/<?php echo $_SESSION['country'] ?>/?edit=<?php echo $row['id'] ?>"
                           class="button buttonPrimary">Edit</a>
                        <a href="<?php echo URL; ?>city/delete?delete=<?php echo $row['id'] ?>"
                           class="button buttonDanger">Delete</a>
                    </td>
                </tr>
            <?php endwhile;
        } ?>
    </table>
</div>
<div class="container">
    <a href="<?php echo URL ?>city/index/<?php echo $data['country'] ?>?pageno=1">First</a>
    <a href="<?php if ($_SESSION['pageno'] <= 1) {
        echo '#';
    } else {
        echo URL . "city/index/" . $data['country'] . "?pageno=" . ($_SESSION['pageno'] - 1);
    } ?>">Prev</a>
    <a href="<?php if ($_SESSION['pageno'] >= $_SESSION['total_pages']) {
        echo '#';
    } else {
        echo URL . "city/index/" . $data['country'] . "?pageno=" . ($_SESSION['pageno'] + 1);
    } ?>">Next</a>
    <a href="<?php echo URL ?>city/index/<?php echo $data['country'] ?>?pageno=<?php echo $_SESSION['total_pages']; ?>">Last</a>
</div>
<div class="center">
    <form action="<?php echo isset($data['edit']) ? URL . 'city/update' : URL . 'city/create/' . $data['country'] ?>"
          method="post">
        <input type="hidden" name="id" value="<?php echo isset($data['city']) ? $data['city']['id'] : ''; ?>">
        Name:<br>
        <input type="text" name="name" value="<?php echo isset($data['city']) ? $data['city']['name'] : ''; ?>"
               placeholder="Enter city name">
        <br>
        Area:<br>
        <input type="text" name="area" value="<?php echo isset($data['city']) ? $data['city']['area'] : ''; ?>"
               placeholder="Enter city area">
        <br>
        People Count:<br>
        <input type="text" name="people_count"
               value="<?php echo isset($data['city']) ? $data['city']['people_count'] : ''; ?>"
               placeholder="Enter city count">
        <br>
        Post code:<br>
        <input type="text" name="post_code"
               value="<?php echo isset($data['city']) ? $data['city']['post_code'] : ''; ?>"
               placeholder="Enter city post code">
        <br><br>
        <button type="submit" class="button buttonSuccess"
                name="<?php echo isset($data['edit']) ? 'update' : 'save' ?>">
            <?php echo isset($data['edit']) ? 'Update' : 'Save' ?>
        </button>
    </form>
</div>
</body>
</html>
