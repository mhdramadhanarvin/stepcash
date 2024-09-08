export default function ApplicationLogo({ className = "", ...props }) {
    return (
        <div className={className} {...props}>
            <img src="images/logo.png" />
        </div>
    );
}
