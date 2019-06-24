<?php

class Home extends Controller
{
    public function index()
    {
        $result = $this->getAll();
        $this->render('home/index', ['countries' => $result]);
    }

    public function create()
    {
        if (isset($_POST['save']))
        {
            $name = $_POST['name'];
            $area = $_POST['area'];
            $people_count = $_POST['people_count'];
            $phone_code = $_POST['phone_code'];
            $time = date("Y-m-d H:i:s");

            $conn = OpenCon();
            $sql = "INSERT INTO country (name, area, people_count, phone_code, add_date) VALUES('$name', '$area', '$people_count', '$phone_code', '$time')";
            $conn->query($sql);
            CloseCon($conn);
        }

        $result = $this->getAll();
        $this->render('home/index', ['countries' => $result]);
    }

    public function update()
    {

        if (isset($_GET['edit']))
        {
            $id = $_GET['edit'];

            $conn = OpenCon();
            $sql = "SELECT * FROM country WHERE id=$id";
            $country = $conn->query($sql);
            CloseCon($conn);
            $country = $country->fetch_assoc();

            $result = $this->getAll();
            unset($_GET['edit']);
            $this->render('home/index', ['countries' => $result, 'country' => $country, 'edit' => true]);
        }

        if (isset($_POST['update']))
        {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $area = $_POST['area'];
            $people_count = $_POST['people_count'];
            $phone_code = $_POST['phone_code'];

            $conn = OpenCon();
            $sql =
                "
                UPDATE country
                SET name ='$name', area = '$area', people_count = '$people_count', phone_code = '$phone_code'
                WHERE id = $id
                ";
            $conn->query($sql);
            CloseCon($conn);

            $result = $this->getAll();
            $this->render('home/index', ['countries' => $result]);
        }
    }

    public function delete()
    {
        if (isset($_GET['delete']))
        {
            $id = $_GET['delete'];

            $conn = OpenCon();
            $sql = "DELETE FROM country WHERE id=$id";
            $conn->query($sql);
            CloseCon($conn);
        }

        $result = $this->getAll();
        $this->render('home/index', ['countries' => $result]);
    }

    private function getSearchData($query)
    {
        $paginator = $this->calcPaginator();

        $offset = $paginator['offset'];
        $recordsPerPage = $paginator['recordsPerPage'];

        $conn = OpenCon();
        $sql = "SELECT * FROM country
            WHERE (`name` LIKE '%" . $query . "%') OR (`area` LIKE '%" . $query . "%') 
            OR (`people_count` LIKE '%" . $query . "%') OR (`phone_code` LIKE '%" . $query . "%')  LIMIT $offset, $recordsPerPage";
        $result = $conn->query($sql);
        CloseCon($conn);

        return $result;
    }

    private function doSearch()
    {
        $query = $_SESSION['query'];

        if ($query == '')
        {
            $this->clearSearch();
            $result = $this->getAll();
            $this->render('home/index', ['countries' => $result]);
        }
        else
        {
            $result = $this->getSearchData($query);
            return ['view' => 'home/index', 'data' => ['countries' => $result]];
        }
    }

    public function search()
    {
        if (isset($_GET['query']))
        {
            $this->clearFilter();
            $this->clearSortDir();
            $query = $_GET['query'];
            $_SESSION['query'] = $query;

            if ($query == '')
            {
                $this->clearSearch();
                $result = $this->getAll();
                $this->render('home/index', ['countries' => $result]);
            }
            else
            {
                $result = $this->getSearchData($query);
                $this->render('home/index', ['countries' => $result]);
            }
        }
    }

    private function getFilteredData($from, $to)
    {
        $paginator = $this->calcPaginator();

        $offset = $paginator['offset'];
        $recordsPerPage = $paginator['recordsPerPage'];

        $conn = OpenCon();
        $sql = "SELECT * FROM country
            WHERE (`add_date` > '$from') AND (`add_date` < '$to')  LIMIT $offset, $recordsPerPage";
        $result = $conn->query($sql);
        CloseCon($conn);

        return $result;
    }

    private function doFiltering()
    {
        $from = $_SESSION['from'];
        $to = $_SESSION['to'];

        if ($from == '' || $to == '')
        {
            $this->clearFilter();
            $result = $this->getAll();
            $this->render('home/index', ['countries' => $result]);
        }
        else
        {
            $result = $this->getFilteredData($from, $to);
            return ['view' => 'home/index', 'data' => ['countries' => $result]];
        }
    }

    public function filter()
    {
        if (isset($_GET['filter']))
        {
            $this->clearSearch();
            $this->clearSortDir();
            $from = $_GET['from'];
            $to = $_GET['to'];
            $_SESSION['filter'] = $_GET['filter'];
            $_SESSION['from'] = $from;
            $_SESSION['to'] = $to;

            if ($from == '' || $to == '')
            {
                $this->clearFilter();
                $result = $this->getAll();
                $this->render('home/index', ['countries' => $result]);
            }
            else
            {
                $result = $this->getFilteredData($from, $to);
                $this->render('home/index', ['countries' => $result]);
            }
        }
    }

    public function clear()
    {
        $this->clearFilter();
        $this->clearSearch();
        $this->clearSortDir();

        $result = $this->getAll();
        $this->render('home/index', ['countries' => $result]);
    }

    private function clearFilter()
    {
        if (isset($_SESSION['filter']))
        {
            unset($_SESSION['filter']);
            unset($_SESSION['from']);
            unset($_SESSION['to']);
        }
    }

    private function clearSearch()
    {
        if (isset($_SESSION['query']))
        {
            unset($_SESSION['query']);
        }
    }

    private function clearSortDir()
    {
        if (isset($_SESSION['dir']))
        {
            unset($_SESSION['dir']);
        }
    }

    private function getSortedData($dir)
    {
        $paginator = $this->calcPaginator();

        $offset = $paginator['offset'];
        $recordsPerPage = $paginator['recordsPerPage'];

        $conn = OpenCon();
        $sql = "SELECT * FROM country ORDER BY `name` $dir LIMIT $offset, $recordsPerPage";
        $result = $conn->query($sql);
        CloseCon($conn);

        return $result;
    }

    private function getAll()
    {
        $result = [];
        $paginator = $this->calcPaginator();

        $offset = $paginator['offset'];
        $recordsPerPage = $paginator['recordsPerPage'];
        if (isset($_GET['sort']))
        {
            $this->clearFilter();
            $this->clearSearch();
            $_SESSION['dir'] = $_SESSION['dir'] === "DESC" ? "ASC" : "DESC";
            $dir = $_SESSION['dir'];

            $result = $this->getSortedData($dir);
        }
        else if (isset($_SESSION['dir']))
        {
            $dir = $_SESSION['dir'];

            $result = $this->getSortedData($dir);
        }
        else if (isset($_SESSION['query']))
        {
            $renderData = $this->doSearch();
            $this->render($renderData['view'], $renderData['data']);
        }
        else if (isset($_SESSION['filter']))
        {
            $renderData = $this->doFiltering();
            $this->render($renderData['view'], $renderData['data']);
        }
        else
        {
            $conn = OpenCon();
            $sql = "SELECT * FROM country LIMIT $offset, $recordsPerPage";
            $result = $conn->query($sql);
            CloseCon($conn);
        }

        return $result;
    }

    private function calcPaginator()
    {
        $conn = OpenCon();
        $pageno = 1;

        if (isset($_GET['pageno']))
        {
            $pageno = $_GET['pageno'];
        }

        $_SESSION['pageno'] = $pageno;
        $recordsPerPage = 10;
        $offset = ($pageno - 1) * $recordsPerPage;

        $total_pages_sql = "SELECT COUNT(*) FROM country";
        $result = $conn->query($total_pages_sql);
        $total_rows = mysqli_fetch_array($result)[0];
        $total_pages = ceil($total_rows / $recordsPerPage);
        CloseCon($conn);

        $_SESSION['total_pages'] = $total_pages;

        return ['offset' => $offset, 'recordsPerPage' => $recordsPerPage];
    }

    private function render($view, $data)
    {
        $this->view($view, $data);
    }
}
