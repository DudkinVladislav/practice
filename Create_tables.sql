USE [TaskBase]
GO

/****** Object:  Table [dbo].[friends]    Script Date: 17.07.2025 12:04:10 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE  [dbo].[friends](
	[request_nomer] [int] NOT NULL,
	[become_time] [datetime] NOT NULL,
	[nomer_one] [int] NOT NULL,
	[nomer_two] [int] NOT NULL
) ON [PRIMARY]

CREATE TABLE [dbo].[friends_request](
	[nomer_send] [int] NOT NULL,
	[nomer_recv] [int] NOT NULL,
	[request_time] [datetime] NOT NULL,
	[see] [nvarchar](50) NULL,
	[answer] [nvarchar](50) NULL,
	[request_nomer] [int] IDENTITY(1,1) NOT NULL,
 CONSTRAINT [PK_friends_request1] PRIMARY KEY CLUSTERED 
(
	[request_nomer] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [dbo].[groups](
	[group_name] [nvarchar](max) NOT NULL,
	[creator_nomer] [int] NOT NULL,
	[access_mode] [nvarchar](10) NULL,
	[groups_nomer] [int] IDENTITY(1,1) NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

CREATE TABLE [dbo].[habit_days](
	[habit_nomer] [int] NOT NULL,
	[day] [date] NOT NULL,
	[do_or_do_not] [nvarchar](50) NULL
) ON [PRIMARY]

CREATE TABLE [dbo].[habits](
	[habit_nomer] [int] IDENTITY(1,1) NOT NULL,
	[user_nomer] [int] NOT NULL,
	[habit_name] [nvarchar](max) NOT NULL,
	[start_date] [date] NOT NULL,
	[public_habit] [int] NOT NULL,
	[end_date] [date] NULL,
	[success] [nvarchar](10) NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

CREATE TABLE [dbo].[login_password](
	[nomer] [int] NOT NULL,
	[login] [nvarchar](max) NOT NULL,
	[password] [nvarchar](max) NOT NULL,
 CONSTRAINT [PK_login_password] PRIMARY KEY CLUSTERED 
(
	[nomer] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

CREATE TABLE [dbo].[messages](
	[message] [nvarchar](max) NULL,
	[nomer_send] [int] NOT NULL,
	[nomer_recv] [int] NOT NULL,
	[user_read] [int] NULL,
	[send_time] [datetime] NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

CREATE TABLE [dbo].[posts](
	[author_nomer] [int] NOT NULL,
	[group_nomer] [int] NOT NULL,
	[publication_date] [datetime] NOT NULL,
	[post] [nvarchar](max) NOT NULL,
	[posts_nomer] [int] IDENTITY(1,1) NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

CREATE TABLE [dbo].[share_habits](
	[habit_nomer] [int] NOT NULL,
	[viewer_nomer] [int] NOT NULL,
	[seen] [nvarchar](10) NOT NULL,
	[nomer_share] [int] IDENTITY(1,1) NOT NULL
) ON [PRIMARY]

CREATE TABLE [dbo].[user_group](
	[nomer] [int] NOT NULL,
	[groups_nomer] [int] NOT NULL
) ON [PRIMARY]

CREATE TABLE [dbo].[users](
	[nomer] [int] IDENTITY(1,1) NOT NULL,
	[nickname] [nvarchar](max) NOT NULL,
	[email] [nvarchar](50) NOT NULL,
	[biography] [nvarchar](max) NULL,
	[message_mode] [int] NULL,
	[date] [datetime] NULL,
	[registration_date] [datetime] NULL,
 CONSTRAINT [PK_users] PRIMARY KEY CLUSTERED 
(
	[nomer] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

CREATE TABLE [dbo].[users_recv](
	[login] [nvarchar](max) NOT NULL,
	[nomer] [int] NOT NULL,
 CONSTRAINT [PK_users_recv] PRIMARY KEY CLUSTERED 
(
	[nomer] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

CREATE TABLE [dbo].[who_saw_post](
	[nomer] [int] NOT NULL,
	[posts_nomer] [int] NOT NULL
) ON [PRIMARY]

GO


