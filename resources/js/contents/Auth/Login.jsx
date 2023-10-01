import {useEffect} from "react";

const Login = ({setPageTitle, setBreadcrumbs}) => {
    const pageTitle = 'Login';

    useEffect(() => {
        setPageTitle(pageTitle);
    }, []);

    useEffect(() => {
        setBreadcrumbs([
            {title: 'Login', url: '/login'}
        ])
    }, []);


    return (
        <h1>Login Page</h1>
    );
}

export default Login;
