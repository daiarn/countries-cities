<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>css/main.css">
    <title>Countries</title>
</head>
<body>

<div class="container">
    <form action="<?php echo URL; ?>home/search" method="get">
        <input type="text" name="query" placeholder="Enter something to search"
               value="<?php echo $_SESSION['query'] ?>"/>
        <input type="submit" value="Search"/>
    </form>
</div>

<div class="container">
    <div class="centerText">
        <h2>Table of countries</h2>
    </div>
    <a href="<?php echo URL ?>home/clear" class="button">Clear search and filter</a>
    <form action="<?php echo URL ?>home/filter/" method="get">
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
    <form action="<?php echo URL . "?pageno=" . $_SESSION['pageno'] . "?sort=" . $_SESSION['sort'] ?>" method="get">
        <input type="hidden" name="pageno" value="<?php echo $_SESSION['pageno'] ?>">
        <button type="submit" class="button buttonSuccess" name="sort">Sort</button>
    </form>
    <table id="myTable">
        <tr>
            <th>Name</th>
            <th>Area</th>
            <th>People Count</th>
            <th>Phone code</th>
            <th>Action</th>
        </tr>
        <?php if (isset($data)) {
            while ($row = $data['countries']->fetch_assoc()): ?>
                <tr>
                    <td><a href="<?php echo URL ?>city/index/<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a>
                    </td>
                    <td><?php echo $row['area'] ?></td>
                    <td><?php echo $row['people_count'] ?></td>
                    <td><?php echo $row['phone_code'] ?></td>
                    <td>
                        <a href="<?php echo URL; ?>home/update?edit=<?php echo $row['id'] ?>"
                           class="button buttonPrimary">Edit</a>
                        <a href="<?php echo URL; ?>home/delete?delete=<?php echo $row['id'] ?>"
                           class="button buttonDanger">Delete</a>
                    </td>
                </tr>
            <?php endwhile;
        } ?>
    </table>
</div>
<div class="container">
    <a href="<?php echo URL ?>?pageno=1">First</a>
    <a href="<?php if ($_SESSION['pageno'] <= 1) {
        echo '#';
    } else {
        echo URL . "?pageno=" . ($_SESSION['pageno'] - 1);
    } ?>">Prev</a>
    <a href="<?php if ($_SESSION['pageno'] >= $_SESSION['total_pages']) {
        echo '#';
    } else {
        echo URL . "?pageno=" . ($_SESSION['pageno'] + 1);
    } ?>">Next</a>
    <a href="<?php echo URL ?>?pageno=<?php echo $_SESSION['total_pages']; ?>">Last</a>
</div>
<div class="center">
    <form action="<?php echo isset($data['edit']) ? URL . 'home/update' : URL . 'home/create' ?>" method="post">
        <input type="hidden" name="id" value="<?php echo isset($data['country']) ? $data['country']['id'] : ''; ?>">
        Name:<br>
        <input type="text" name="name" value="<?php echo isset($data['country']) ? $data['country']['name'] : ''; ?>"
               placeholder="Enter country name">
        <br>
        Area:<br>
        <input type="text" name="area" value="<?php echo isset($data['country']) ? $data['country']['area'] : ''; ?>"
               placeholder="Enter country area">
        <br>
        People Count:<br>
        <input type="text" name="people_count"
               value="<?php echo isset($data['country']) ? $data['country']['people_count'] : ''; ?>"
               placeholder="Enter people count">
        <br>
        Phone code:<br>
        <input type="text" name="phone_code"
               value="<?php echo isset($data['country']) ? $data['country']['phone_code'] : ''; ?>"
               placeholder="Enter country phone code">
        <br><br>
        <button type="submit" class="button buttonSuccess"
                name="<?php echo isset($data['edit']) ? 'update' : 'save' ?>">
            <?php echo isset($data['edit']) ? 'Update' : 'Save' ?>
        </button>
    </form>
</div>
</body>
</html>
