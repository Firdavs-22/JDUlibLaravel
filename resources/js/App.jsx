import {useState, useEffect} from "react";
import {BrowserRouter, Routes, Route} from "react-router-dom";

import Navigation from "./components/Navigation/Navigation";
import Sidebar from "./components/Sidebar/Sidebar";
import Footer from "./components/Footer/Footer";
import Content from "./components/Content/Content";
import Login from "./contents/Auth/Login";
import Index from "./contents/User/Index";


const APP_NAME = 'JDULib'
const App = () => {
    const [page, setPage] = useState('Main');
    const [breadcrumb, setBreadcrumb] = useState([
        {title: '', url: ''}
    ]);
    const [sidebar, setSidebar] = useState([]);

    useEffect(() => {
        setSidebar([
            // {title: 'test', isHeader: true},
            {title: 'Users', icon: 'fas fa-users', url: '/user'},
            {title: 'Login', icon: 'fas fa-sign-in-alt', url: '/login'},
        ])
    }, []);

    const setPageTitle = (title) => {
        setPage(title);
    }
    const setBreadcrumbs = (path) => {
        setBreadcrumb(path)
    }


    return (
        <BrowserRouter>
            {/*<div className="preloader flex-column justify-content-center align-items-center">*/}
            {/*    <img src="/dist/img/AdminLTELogo.png" alt="AdminLTELogo" style={{height: '60px', width: '60px'}}/>*/}
            {/*</div>*/}
            <Navigation/>
            <Sidebar appName={APP_NAME} username={'John Doe'} elements={sidebar}/>
            <Content contentName={page} breadcrumb={breadcrumb}>
                <Routes>
                    <Route path="/" element={
                        <Index setPageTitle={setPageTitle} setBreadcrumbs={setBreadcrumbs}/>
                    }/>
                    <Route path="/user" element={
                        <Index setPageTitle={setPageTitle} setBreadcrumbs={setBreadcrumbs}/>
                    }/>
                    <Route path="/login" element={
                        <Login setPageTitle={setPageTitle} setBreadcrumbs={setBreadcrumbs}/>
                    }/>
                </Routes>
            </Content>
            <Footer/>

        </BrowserRouter>
    )
}
export default App
