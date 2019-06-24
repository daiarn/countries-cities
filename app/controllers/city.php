<?php


class City extends Controller
{

    public function index($id = '')
    {
        if ($id !== '')
        {
            $_SESSION['country'] = $id;
        }
        else
        {
            $id = $_SESSION['country'];
        }
        $result = $this->getAllById($id);
        $this->render('home/cities', ['cities' => $result, 'country' => $id]);
    }

    public function create()
    {
        $id = $_SESSION['country'];

        if (isset($_POST['save'])) {
            $name = $_POST['name'];
            $area = $_POST['area'];
            $people_count = $_POST['people_count'];
            $post_code = $_POST['post_code'];
            $time = date("Y-m-d H:i:s");

            $conn = OpenCon();
            $sql = "INSERT INTO city (name, area, people_count, post_code, country_id, add_date) VALUES('$name', '$area', '$people_count', '$post_code', '$id', '$time')";
            $conn->query($sql);
            CloseCon($conn);
        }
        $result = $this->getAllById($id);
        $this->render('home/cities', ['cities' => $result, 'country' => $id]);
    }

    public function update()
    {
        $countryId = $_SESSION['country'];

        if (isset($_GET['edit']))
        {
            $id = $_GET['edit'];

            $conn = OpenCon();
            $sql = "SELECT * FROM city WHERE id=$id";
            $country = $conn->query($sql);
            CloseCon($conn);
            $country = $country->fetch_assoc();

            $result = $this->getAllById($countryId);
            $this->render('home/cities', ['cities' => $result, 'city' => $country, 'country' => $countryId, 'edit' => true]);
        }

        if (isset($_POST['update']))
        {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $area = $_POST['area'];
            $people_count = $_POST['people_count'];
            $post_code = $_POST['post_code'];

            $conn = OpenCon();
            $sql =
                "
                UPDATE city
                SET name ='$name', area = '$area', people_count = '$people_count', post_code = '$post_code'
                WHERE id = $id
                ";
            $conn->query($sql);
            CloseCon($conn);

            $result = $this->getAllById($countryId);
            $this->render('home/cities', ['cities' => $result, 'country' => $countryId]);
        }
    }

    public function delete()
    {
        $countryId = $_SESSION['country'];

        if (isset($_GET['delete']))
        {
            $id = $_GET['delete'];

            $conn = OpenCon();
            $sql = "DELETE FROM city WHERE id=$id";
            $conn->query($sql);
            CloseCon($conn);
        }

        $result = $this->getAllById($countryId);
        $this->view('home/cities', ['cities' => $result, 'country' => $countryId]);
        $this->render('home/cities', ['cities' => $result, 'country' => $countryId]);
    }

    private function getSearchData($query)
    {
        $countryId = $_SESSION['country'];
        $paginator = $this->calcPaginator();

        $offset = $paginator['offset'];
        $recordsPerPage = $paginator['recordsPerPage'];

        $conn = OpenCon();
        $sql = "SELECT * FROM city
            WHERE ((`name` LIKE '%" . $query . "%') 
            OR (`area` LIKE '%" . $query . "%') 
            OR (`people_count` LIKE '%" . $query . "%') 
            OR (`post_code` LIKE '%" . $query . "%'))
            AND (`country_id` ='$countryId')  
            LIMIT $offset, $recordsPerPage";
        $result = $conn->query($sql);
        CloseCon($conn);

        return $result;
    }

    private function doSearch()
    {
        $countryId = $_SESSION['country'];
        $query = $_SESSION['query'];

        if ($query == '')
        {
            $this->clearSearch();
            $result = $this->getAllById($countryId);
            $this->render('home/cities', ['cities' => $result, 'country' => $countryId]);
        }
        else
        {
            $result = $this->getSearchData($query);
            return ['view' => 'home/cities', 'data' => ['cities' => $result, 'country' => $countryId]];
        }
    }

    public function search()
    {
        $countryId = $_SESSION['country'];

        if (isset($_GET['query']))
        {
            $this->clearFilter();
            $this->clearSortDir();
            $query = $_GET['query'];
            $_SESSION['query'] = $query;

            if ($query == '')
            {
                $this->clearSearch();
                $result = $this->getAllById($countryId);
                $this->render('home/cities', ['cities' => $result, 'country' => $countryId]);
            }
            else
            {
                $result = $this->getSearchData($query);
                $this->render('home/cities', ['cities' => $result, 'country' => $countryId]);
            }
        }
    }

    private function getFilteredData($from, $to)
    {
        $countryId = $_SESSION['country'];
        $paginator = $this->calcPaginator();

        $offset = $paginator['offset'];
        $recordsPerPage = $paginator['recordsPerPage'];

        $conn = OpenCon();
        $sql = "SELECT * FROM city
            WHERE (`add_date` > '$from') AND (`add_date` < '$to') AND (`country_id` = $countryId) 
            LIMIT $offset, $recordsPerPage";
        $result = $conn->query($sql);
        CloseCon($conn);

        return $result;
    }

    private function doFiltering()
    {
        $countryId = $_SESSION['country'];
        $from = $_SESSION['from'];
        $to = $_SESSION['to'];

        if ($from == '' || $to == '')
        {
            $this->clearFilter();
            $result = $this->getAllById($countryId);
            $this->render('home/cities', ['cities' => $result, 'country' => $countryId]);
        }
        else
        {
            $result = $this->getFilteredData($from, $to);
            return ['view' => 'home/cities', 'data' => ['cities' => $result, 'country' => $countryId]];
        }
    }

    public function filter()
    {
        $countryId = $_SESSION['country'];

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
                $result = $this->getAllById($countryId);
                $this->render('home/cities', ['cities' => $result, 'country' => $countryId]);
            }
            else
            {
                $result = $this->getFilteredData($from, $to);
                $this->view('home/cities', ['cities' => $result, 'country' => $countryId]);
            }
        }
    }

    public function clear()
    {
        $countryId = $_SESSION['country'];

        $this->clearFilter();
        $this->clearSearch();
        $this->clearSortDir();

        $result = $this->getAllById($countryId);
        $this->render('home/cities', ['cities' => $result, 'country' => $countryId]);
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

    private function getSortedData($id, $dir)
    {
        $paginator = $this->calcPaginator();

        $offset = $paginator['offset'];
        $recordsPerPage = $paginator['recordsPerPage'];

        $conn = OpenCon();
        $sql = "SELECT * FROM city WHERE country_id = $id ORDER BY `name` $dir LIMIT $offset, $recordsPerPage";
        $result = $conn->query($sql);
        CloseCon($conn);

        return $result;
    }

    private function getAllById($id)
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
            $result = $this->getSortedData($id, $dir);
        }
        elseif (isset($_SESSION['dir']))
        {
            $dir = $_SESSION['dir'];
            $result = $this->getSortedData($id, $dir);
        }
        elseif (isset($_SESSION['query']))
        {
            $renderData = $this->doSearch();
            $this->render($renderData['view'], $renderData['data']);
        }
        elseif (isset($_SESSION['filter']))
        {
            $renderData = $this->doFiltering();
            $this->render($renderData['view'], $renderData['data']);
        }
        else
        {
            $conn = OpenCon();
            $sql = "SELECT * FROM city WHERE country_id = $id LIMIT $offset, $recordsPerPage";
            $result = $conn->query($sql);
            CloseCon($conn);
        }

        return $result;
    }

    private function calcPaginator()
    {
        $countryId = $_SESSION['country'];
        $conn = OpenCon();
        $pageno = 1;

        if (isset($_GET['pageno']))
        {
            $pageno = $_GET['pageno'];
        }

        $_SESSION['pageno'] = $pageno;
        $recordsPerPage = 10;
        $offset = ($pageno - 1) * $recordsPerPage;

        $total_pages_sql = "SELECT COUNT(*) FROM city WHERE country_id = $countryId";
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