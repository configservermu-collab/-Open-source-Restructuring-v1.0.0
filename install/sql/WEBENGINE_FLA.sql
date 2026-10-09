
/****** Object:  Table [dbo].[WEBENGINE_FLA]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS OFF

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING OFF

CREATE TABLE [dbo].[WEBENGINE_FLA](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[username] [varchar](50) NOT NULL,
	[ip_address] [varchar](50) NOT NULL,
	[unlock_timestamp] [varchar](50) NOT NULL,
	[failed_attempts] [int] NOT NULL,
	[timestamp] [varchar](50) NOT NULL
) ON [PRIMARY]


SET ANSI_PADDING OFF

SET IDENTITY_INSERT [dbo].[WEBENGINE_FLA] ON 

INSERT [dbo].[WEBENGINE_FLA] ([id], [username], [ip_address], [unlock_timestamp], [failed_attempts], [timestamp]) VALUES (1, N'edramgar', N'187.184.11.63', N'0', 1, N'1748994763')
INSERT [dbo].[WEBENGINE_FLA] ([id], [username], [ip_address], [unlock_timestamp], [failed_attempts], [timestamp]) VALUES (4, N'edramgar', N'187.226.21.159', N'1750452222', 5, N'1750451322')
INSERT [dbo].[WEBENGINE_FLA] ([id], [username], [ip_address], [unlock_timestamp], [failed_attempts], [timestamp]) VALUES (1, N'edramgar', N'187.184.11.63', N'0', 1, N'1748994763')
INSERT [dbo].[WEBENGINE_FLA] ([id], [username], [ip_address], [unlock_timestamp], [failed_attempts], [timestamp]) VALUES (4, N'edramgar', N'187.226.21.159', N'1750452222', 5, N'1750451322')
SET IDENTITY_INSERT [dbo].[WEBENGINE_FLA] OFF


