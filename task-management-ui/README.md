# Task Management UI

A modern React-based task management application built with Create React App, featuring user authentication, task CRUD operations, and a responsive interface.

## Features

- **User Authentication**: Login, registration, and session management
- **Task Management**: Create, read, update, and delete tasks
- **Responsive Design**: Works seamlessly on desktop and mobile devices
- **Real-time Updates**: Instant feedback with toast notifications
- **Modern UI**: Clean and intuitive user interface

## Prerequisites

Before you begin, ensure you have the following installed:

- **Node.js** (version 14 or higher)
- **npm** (version 6 or higher) or **yarn** (version 1.22 or higher)
- **Git** (for cloning the repository)

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd task-management-ui
```

### 2. Install Dependencies

Using npm:
```bash
npm install
```

Or using yarn:
```bash
yarn install
```

### 3. Environment Configuration

Create a `.env` file in the root directory and add your environment variables:

```env
REACT_APP_API_URL=http://localhost:8000/api
REACT_APP_API_BASE_URL=http://localhost:8000
```

## Available Scripts

In the project directory, you can run:

### `npm start` or `yarn start`

Runs the app in the development mode.\
Open [http://localhost:3000](http://localhost:3000) to view it in your browser.

The page will reload when you make changes.\
You may also see any lint errors in the console.

### `npm test` or `yarn test`

Launches the test runner in the interactive watch mode.\
See the section about [running tests](https://facebook.github.io/create-react-app/docs/running-tests) for more information.

### `npm run build` or `yarn build`

Builds the app for production to the `build` folder.\
It correctly bundles React in production mode and optimizes the build for the best performance.

The build is minified and the filenames include the hashes.\
Your app is ready to be deployed!

### `npm run eject` or `yarn eject`

**Note: this is a one-way operation. Once you `eject`, you can't go back!**

If you aren't satisfied with the build tool and configuration choices, you can `eject` at any time. This command will remove the single build dependency from your project.

## Running the Application

### Development Mode

1. **Start the development server:**
   ```bash
   npm start
   # or
   yarn start
   ```

2. **Open your browser** and navigate to [http://localhost:3000](http://localhost:3000)

3. **The app will automatically reload** when you make changes to the code.

### Production Mode

1. **Build the application:**
   ```bash
   npm run build
   # or
   yarn build
   ```

2. **Serve the built files** using a static file server:
   ```bash
   # Using serve (install with: npm install -g serve)
   serve -s build
   
   # Or using Python
   python -m http.server 8000
   ```

## Dependencies

### Core Dependencies
- **React** (19.2.5): JavaScript library for building user interfaces
- **React DOM** (19.2.5): React package for working with the DOM
- **React Router DOM** (7.14.1): Declarative routing for React
- **Axios** (1.15.0): Promise-based HTTP client for API requests
- **React Toastify** (11.1.0): Toast notifications for React

### Development Dependencies
- **React Scripts** (5.0.1): Create React App configuration and scripts
- **Testing Library**: Testing utilities for React applications
- **Web Vitals** (2.1.4): Performance metrics

## Project Structure

```
task-management-ui/
|
|--- public/                 # Static files
|    |--- index.html         # HTML template
|    |--- favicon.ico        # Favicon
|    |--- manifest.json      # PWA manifest
|
|--- src/                    # Source code
|    |--- components/        # Reusable components
|    |--- pages/            # Page components
|    |--- services/         # API services
|    |--- utils/            # Utility functions
|    |--- App.js            # Main App component
|    |--- index.js          # Entry point
|    |--- index.css         # Global styles
|
|--- package.json           # Project dependencies and scripts
|--- README.md              # This file
|--- .env                   # Environment variables (create this)
|--- .gitignore            # Git ignore rules
```

## API Integration

This application connects to a backend API. Make sure your backend server is running before starting the React app.

**Default API Configuration:**
- Base URL: `http://localhost:8000/api`
- Authentication: JWT tokens
- Content-Type: `application/json`

## Troubleshooting

### Common Issues

1. **Port Already in Use**
   ```bash
   # Kill process on port 3000
   npx kill-port 3000
   # Or use different port
   PORT=3001 npm start
   ```

2. **Dependency Installation Issues**
   ```bash
   # Clear npm cache
   npm cache clean --force
   
   # Delete node_modules and package-lock.json
   rm -rf node_modules package-lock.json
   
   # Reinstall dependencies
   npm install
   ```

3. **CORS Issues**
   - Ensure your backend API allows requests from `http://localhost:3000`
   - Check that CORS headers are properly configured on the server

4. **Build Errors**
   ```bash
   # Check for TypeScript errors
   npm run build --verbose
   
   # Validate dependencies
   npm audit
   ```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Deployment

### Netlify
1. Connect your repository to Netlify
2. Set build command: `npm run build`
3. Set publish directory: `build`
4. Add environment variables in Netlify dashboard

### Vercel
1. Install Vercel CLI: `npm i -g vercel`
2. Run: `vercel`
3. Follow the prompts to deploy

### Static Hosting
```bash
# Build and deploy to any static hosting service
npm run build
# Upload the 'build' folder to your hosting provider
```

## Learn More

- **React Documentation**: [https://reactjs.org/](https://reactjs.org/)
- **Create React App Documentation**: [https://facebook.github.io/create-react-app/](https://facebook.github.io/create-react-app/)
- **React Router**: [https://reactrouter.com/](https://reactrouter.com/)
- **Axios Documentation**: [https://axios-http.com/](https://axios-http.com/)

## License

This project is licensed under the MIT License - see the LICENSE file for details.
